<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface;

class Ideal extends BaseHeidelpayPaymentMethod implements PaymentWithPostSaveOrderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function postSaveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        $redirectUrl = $this->getCheckoutRedirectUrlFromAuthorizeTransactionLog(
            $checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder()
        );

        $this->setExternalRedirect($redirectUrl, $checkoutResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $transactionLogTransfer
     *
     * @return string
     */
    protected function getAuthorizeRedirectUrl(HeidelpayTransactionLogTransfer $transactionLogTransfer): string
    {
        return $this->config->getIdealAuthorizeUrl();
    }
}
