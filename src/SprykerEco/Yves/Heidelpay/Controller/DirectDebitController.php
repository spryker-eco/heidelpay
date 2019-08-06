<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Controller;

use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerEco\Yves\Heidelpay\Plugin\Provider\HeidelpayControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayFactory getFactory()
 * @method \SprykerEco\Client\Heidelpay\HeidelpayClientInterface getClient()
 */
class DirectDebitController extends BaseHeidelpayController
{
    protected const REQUEST_PARAM_REGISTRATION_ID = 'id_registration';
    protected const ERROR_CODE_REGISTRATION_NOT_FOUND = 'registration_not_found';
    protected const ERROR_CODE_QUOTE_EXPIRED = 'quote_expired';
    protected const URL_PARAM_ERROR_CODE = 'error_code';
    protected const PATH_CHECKOUT_SUMMARY = 'checkout-summary';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registrationRequestAction(Request $request): Response
    {
        $apiResponseAsArray = $this->getUrldecodedRequestBody($request);

        $registrationResponseTransfer = $this->parseDirectDebitRegistrationResponse(
            $this->getClient()->filterResponseParameters($apiResponseAsArray)
        );

        if ($registrationResponseTransfer->getIsError()) {
            return $this->redirectToRegistrationFailureUrl($registrationResponseTransfer);
        }

        $savingResultTransfer = $this->saveDirectDebitRegistration($registrationResponseTransfer);

        return $this->redirectToRegistrationSuccessUrl($savingResultTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|string
     */
    public function registrationSuccessAction(Request $request)
    {
        $idRegistration = $request->get(static::REQUEST_PARAM_REGISTRATION_ID);
        $quoteTransfer = $this->getClient()->getQuote();

        if ($this->isQuoteExpired($quoteTransfer)) {
            return $this->redirectToHeidelpayPaymentFailedUrl(static::ERROR_CODE_QUOTE_EXPIRED);
        }

        $creditCardRegistration = $this->findRegistrationByIdAndQuote($idRegistration, $quoteTransfer);

        if ($creditCardRegistration->getIdCreditCardRegistration() !== null) {
            $this->hydrateCreditCardRegistrationToQuote($creditCardRegistration, $quoteTransfer);

            return $this->redirectToSummaryStep();
        }

        return $this->redirectToHeidelpayPaymentFailedUrl(static::ERROR_CODE_REGISTRATION_NOT_FOUND);
    }

    /**
     * @param array $requestAsArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer
     */
    protected function parseDirectDebitRegistrationResponse(array $requestAsArray): HeidelpayDirectDebitRegistrationResponseTransfer
    {
        return $this->getClient()
            ->parseDirectDebitRegistrationResponse($requestAsArray);
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
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer $registrationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer
     */
    protected function saveDirectDebitRegistration(
        HeidelpayDirectDebitRegistrationResponseTransfer $registrationResponseTransfer
    ): HeidelpayRegistrationSaveResponseTransfer {
        return $this->getClient()
            ->saveDirectDebitRegistration($registrationResponseTransfer);
    }

    /**
     * @param int $idRegistration
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer|null
     */
    protected function findRegistrationByIdAndQuote(int $idRegistration, QuoteTransfer $quoteTransfer): ?HeidelpayCreditCardRegistrationTransfer
    {
        return $this->getClient()
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
        return $this->redirectResponseInternal(static::PATH_CHECKOUT_SUMMARY);
    }

    /**
     * @param string $errorCode
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToHeidelpayPaymentFailedUrl(string $errorCode): RedirectResponse
    {
        return $this->redirectResponseInternal(
            HeidelpayControllerProvider::HEIDELPAY_PAYMENT_FAILED,
            [static::URL_PARAM_ERROR_CODE => $errorCode]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer $registrationRequestTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function redirectToRegistrationFailureUrl(HeidelpayDirectDebitRegistrationResponseTransfer $registrationRequestTransfer): Response
    {
        $redirectUrl = $this->getApplication()
            ->url(
                HeidelpayControllerProvider::HEIDELPAY_PAYMENT_FAILED,
                [static::URL_PARAM_ERROR_CODE => $registrationRequestTransfer->getError()->getCode()]
            );

        return $this->streamRedirectResponse($redirectUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer $saveResponseTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function redirectToRegistrationSuccessUrl(HeidelpayRegistrationSaveResponseTransfer $saveResponseTransfer): Response
    {
        $redirectUrl = $this->getApplication()
            ->url(
                HeidelpayControllerProvider::HEIDELPAY_DIRECT_DEBIT_REGISTER_SUCCESS,
                [static::REQUEST_PARAM_REGISTRATION_ID => $saveResponseTransfer->getIdRegistration()]
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
