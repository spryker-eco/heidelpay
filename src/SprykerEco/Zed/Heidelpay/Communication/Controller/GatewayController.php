<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Communication\Controller;

use Generated\Shared\Transfer\HeidelpayAuthorizeTransactionLogRequestTransfer;
use Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
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
    public function getAuthorizeTransactionLogAction(HeidelpayAuthorizeTransactionLogRequestTransfer $authorizeLogRequestTransfer)
    {
        return $this->getFacade()->getAuthorizeTransactionLog($authorizeLogRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer
     */
    public function getCreditCardPaymentOptionsAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->getCreditCardPaymentOptions($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer $paymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalPaymentResponseAction(HeidelpayExternalPaymentRequestTransfer $paymentRequestTransfer)
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
    ) {
        return $this->getFacade()->saveCreditCardRegistration($registrationRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer|null
     */
    public function findCreditCardRegistrationAction(
        HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
    ) {
        return $this->getFacade()->findCreditCardRegistrationByIdAndQuote($findRegistrationRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return mixed
     */
    public function easycreditRequestAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->initializePayment($quoteTransfer);
    }
}
