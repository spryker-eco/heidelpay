<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Controller;

use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayFactory getFactory()
 * @method \SprykerEco\Client\Heidelpay\HeidelpayClientInterface getClient()
 */
class CreditCardController extends BaseHeidelpayController
{
    const REQUEST_PARAM_REGISTRATION_ID = 'id_registration';
    const ERROR_CODE_REGISTRATION_NOT_FOUND = 'registration_not_found';
    const ERROR_CODE_QUOTE_EXPIRED = 'quote_expired';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registrationRequestAction(Request $request): Response
    {
        $apiResponseAsArray = $this->getUrldecodedRequestBody($request);

        $registrationRequestTransfer = $this->getValidatedRegistrationRequest(
            $this->getClient()->filterResponseParameters($apiResponseAsArray)
        );

        if ($registrationRequestTransfer->getIsError()) {
            return $this->getInvalidApiRequestActionUrl($registrationRequestTransfer);
        }

        $savingResultTransfer = $this->saveCreditCardRegistration($registrationRequestTransfer);

        return $this->getRegistrationSuccessActionUrl($savingResultTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|string
     */
    public function registrationSuccessAction(Request $request)
    {
        $idRegistration = $request->get(static::REQUEST_PARAM_REGISTRATION_ID);
        $quoteTransfer = $this->getClient()->getQuoteFromSession();

        if ($this->isQuoteExpired($quoteTransfer)) {
            return $this->redirectToPaymentStepWithError(static::ERROR_CODE_QUOTE_EXPIRED);
        }

        $creditCardRegistration = $this->findRegistrationByIdAndQuote($idRegistration, $quoteTransfer);

        if ($creditCardRegistration->getIdCreditCardRegistration() !== null) {
            $this->hydrateCreditCardRegistrationToQuote($creditCardRegistration, $quoteTransfer);
            return $this->redirectToSummaryStep();
        }

        return $this->redirectToPaymentStepWithError(static::ERROR_CODE_REGISTRATION_NOT_FOUND);
    }

    /**
     * @param array $requestAsArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer
     */
    protected function getValidatedRegistrationRequest(array $requestAsArray): HeidelpayRegistrationRequestTransfer
    {
        return $this->getClient()->parseExternalResponse($requestAsArray);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteExpired(QuoteTransfer $quoteTransfer): bool
    {
        try {
            $quoteTransfer->requireCustomer();
            $quoteTransfer->getCustomer()->requireEmail();
            $quoteTransfer->requireTotals();
        } catch (RequiredTransferPropertyException $exception) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer
     */
    protected function saveCreditCardRegistration(HeidelpayRegistrationRequestTransfer $registrationRequestTransfer): HeidelpayRegistrationSaveResponseTransfer
    {
        return $this
            ->getClient()
            ->saveCreditCardRegistration($registrationRequestTransfer);
    }

    /**
     * @param int $idRegistration
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer|null
     */
    protected function findRegistrationByIdAndQuote(int $idRegistration, QuoteTransfer $quoteTransfer)
    {
        return $this
            ->getClient()
            ->findRegistrationByIdAndQuote($idRegistration, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer $registrationTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function hydrateCreditCardRegistrationToQuote(
        HeidelpayCreditCardRegistrationTransfer $registrationTransfer,
        QuoteTransfer $quoteTransfer
    ): void {
        $this->getFactory()
            ->createCreditCardRegistrationToQuoteHydrator()
            ->hydrateCreditCardRegistrationToQuote($registrationTransfer, $quoteTransfer);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToSummaryStep(): RedirectResponse
    {
        $summaryStepUrl = $this->getConfig()->getYvesCheckoutSummaryStepUrl();

        return new RedirectResponse($summaryStepUrl);
    }

    /**
     * @param string $errorCode
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToPaymentStepWithError(string $errorCode): RedirectResponse
    {
        $paymentFailedUrl = sprintf(

            $this->getConfig()->getYvesCheckoutPaymentFailedUrl(),
            $errorCode
        );

        return new RedirectResponse($paymentFailedUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer $saveResponseTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function redirectToRegistrationFailedAction(
        HeidelpayRegistrationSaveResponseTransfer $saveResponseTransfer
    ): Response {
        $redirectUrl = sprintf(
            $this->getConfig()->getYvesCheckoutPaymentFailedUrl(),
            $saveResponseTransfer->getError()->getCode()
        );

        return $this->streamRedirectResponse($redirectUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getInvalidApiRequestActionUrl(
        HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
    ): Response {
        $redirectUrl = sprintf(
            $this->getConfig()->getYvesCheckoutPaymentFailedUrl(),
            $registrationRequestTransfer->getError()->getCode()
        );

        return $this->streamRedirectResponse($redirectUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer $saveResponseTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getRegistrationSuccessActionUrl(
        HeidelpayRegistrationSaveResponseTransfer $saveResponseTransfer
    ): Response {
        $redirectUrl = sprintf(
            $this->getConfig()->getYvesRegistrationSuccessUrl(),
            $saveResponseTransfer->getIdRegistration()
        );

        return $this->streamRedirectResponse($redirectUrl);
    }

    /**
     * @param string $redirectUrl
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function streamRedirectResponse(string $redirectUrl): Response
    {
        $callback = function () use ($redirectUrl) {
            echo $redirectUrl;
        };

        return $this->streamedResponse($callback)->send();
    }
}
