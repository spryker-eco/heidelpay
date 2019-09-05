<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Communication\Controller;

use Generated\Shared\Transfer\HeidelpayAuthorizeTransactionLogRequestTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer;
use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerEco\Zed\Heidelpay\Business\HeidelpayFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayAuthorizeTransactionLogRequestTransfer $authorizeLogRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    public function getAuthorizeTransactionLogAction(HeidelpayAuthorizeTransactionLogRequestTransfer $authorizeLogRequestTransfer): HeidelpayTransactionLogTransfer
    {
        return $this->getFacade()->getAuthorizeTransactionLog($authorizeLogRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer
     */
    public function getCreditCardPaymentOptionsAction(QuoteTransfer $quoteTransfer): HeidelpayCreditCardPaymentOptionsTransfer
    {
        return $this->getFacade()->getCreditCardPaymentOptions($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer
     */
    public function getDirectDebitPaymentOptionsAction(QuoteTransfer $quoteTransfer): HeidelpayDirectDebitPaymentOptionsTransfer
    {
        return $this->getFacade()->getDirectDebitPaymentOptions($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer $paymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalPaymentResponseAction(HeidelpayExternalPaymentRequestTransfer $paymentRequestTransfer): HeidelpayPaymentProcessingResponseTransfer
    {
        return $this->getFacade()->processExternalPaymentResponse($paymentRequestTransfer->getBody());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer $paymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalEasyCreditPaymentResponseAction(HeidelpayExternalPaymentRequestTransfer $paymentRequestTransfer)
    {
        return $this->getFacade()->processExternalEasyCreditPaymentResponse($paymentRequestTransfer->getBody());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer
     */
    public function saveCreditCardRegistrationAction(
        HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
    ): HeidelpayRegistrationSaveResponseTransfer {
        return $this->getFacade()->saveCreditCardRegistration($registrationRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $registrationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function saveDirectDebitRegistrationAction(
        HeidelpayDirectDebitRegistrationTransfer $registrationResponseTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        return $this->getFacade()->saveDirectDebitRegistration($registrationResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer
     */
    public function findCreditCardRegistrationAction(
        HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
    ): HeidelpayCreditCardRegistrationTransfer {
        return $this->getFacade()->findCreditCardRegistrationByIdAndQuote($findRegistrationRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $registrationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function retrieveDirectDebitRegistrationAction(
        HeidelpayDirectDebitRegistrationTransfer $registrationResponseTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        return $this->getFacade()->retrieveDirectDebitRegistration($registrationResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function easycreditInitializePaymentAction(QuoteTransfer $quoteTransfer): HeidelpayResponseTransfer
    {
        return $this->getFacade()->initializePayment($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function processNotificationAction(HeidelpayNotificationTransfer $notificationTransfer): HeidelpayNotificationTransfer
    {
        return $this->getFacade()->processNotification($notificationTransfer);
    }
}
