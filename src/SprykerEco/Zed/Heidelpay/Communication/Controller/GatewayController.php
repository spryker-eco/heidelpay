<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Communication\Controller;

use Generated\Shared\Transfer\HeidelpayAuthorizeTransactionLogRequestTransfer;
use Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Heidelpay\Business\HeidelpayFacade getFacade()
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

}
