<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Controller;

use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayResponseErrorTransfer;
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
    /**
     * @var string
     */
    protected const REQUEST_PARAM_REGISTRATION_ID = 'id_registration';
    /**
     * @var string
     */
    protected const URL_PARAM_ERROR_CODE = 'error_code';
    /**
     * @var string
     */
    protected const PATH_CHECKOUT_SUMMARY = 'checkout-summary';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registrationRequestAction(Request $request): Response
    {
        $directDebitRegistrationTransfer = $this->getFactory()
            ->createHeidelpayDirectDebitRegistrationProcessor()
            ->processNewRegistration($request);

        if ($directDebitRegistrationTransfer->getIsError()) {
            return $this->redirectToRegistrationFailureUrl($directDebitRegistrationTransfer->getError());
        }

        return $this->redirectToRegistrationSuccessUrl($directDebitRegistrationTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registrationSuccessAction(Request $request): RedirectResponse
    {
        $directDebitRegistrationTransfer = $this->getFactory()
            ->createHeidelpayDirectDebitRegistrationProcessor()
            ->processSuccessRegistration($request);

        if ($directDebitRegistrationTransfer->getIsError()) {
            return $this->redirectToHeidelpayPaymentFailedUrl($directDebitRegistrationTransfer->getError());
        }

        return $this->redirectToSummaryStep();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToSummaryStep(): RedirectResponse
    {
        return $this->redirectResponseInternal(static::PATH_CHECKOUT_SUMMARY);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayResponseErrorTransfer $responseErrorTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToHeidelpayPaymentFailedUrl(HeidelpayResponseErrorTransfer $responseErrorTransfer): RedirectResponse
    {
        return $this->redirectResponseInternal(
            HeidelpayControllerProvider::HEIDELPAY_PAYMENT_FAILED,
            [static::URL_PARAM_ERROR_CODE => $responseErrorTransfer->getCode()]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayResponseErrorTransfer $responseErrorTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function redirectToRegistrationFailureUrl(HeidelpayResponseErrorTransfer $responseErrorTransfer): Response
    {
        /**
         * @var \Spryker\Yves\Kernel\Application $application
         */
        $application = $this->getApplication();
        $redirectUrl = $application
            ->url(
                HeidelpayControllerProvider::HEIDELPAY_PAYMENT_FAILED,
                [static::URL_PARAM_ERROR_CODE => $responseErrorTransfer->getCode()]
            );

        return $this->streamRedirectResponse($redirectUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function redirectToRegistrationSuccessUrl(HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer): Response
    {
        /**
         * @var \Spryker\Yves\Kernel\Application $application
         */
        $application = $this->getApplication();
        $redirectUrl = $application
            ->url(
                HeidelpayControllerProvider::HEIDELPAY_DIRECT_DEBIT_REGISTER_SUCCESS,
                [static::REQUEST_PARAM_REGISTRATION_ID => $directDebitRegistrationTransfer->getIdDirectDebitRegistration()]
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
