<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface;

class PaypalDebit extends BaseHeidelpayPaymentMethod implements PaymentWithPostSaveOrderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function postSaveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        $redirectUrl = $this->getCheckoutRedirectUrlFromDebitTransactionLog(
            $checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder()
        );

        $this->setExternalRedirect($redirectUrl, $checkoutResponseTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string
     */
    protected function getCheckoutRedirectUrlFromDebitTransactionLog(int $idSalesOrder): string
    {
        $debitTransactionLogTransfer = $this->findOrderDebitTransactionLog($idSalesOrder);

        if (($debitTransactionLogTransfer !== null) &&
            $this->isDebitTransactionSentSuccessfully($debitTransactionLogTransfer)
        ) {
            return $this->getDebitRedirectUrl($debitTransactionLogTransfer);
        }

        return $this->getDebitFailedRedirectUrl($debitTransactionLogTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    protected function findOrderDebitTransactionLog(int $idSalesOrder): HeidelpayTransactionLogTransfer
    {
        return $this->transactionLogManager->findOrderDebitTransactionLog($idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $debitTransactionLogTransfer
     *
     * @return bool
     */
    protected function isDebitTransactionSentSuccessfully(HeidelpayTransactionLogTransfer $debitTransactionLogTransfer): bool
    {
        return $debitTransactionLogTransfer->getHeidelpayResponse()->getIsSuccess();
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $transactionLogTransfer
     *
     * @return string
     */
    protected function getDebitRedirectUrl(HeidelpayTransactionLogTransfer $transactionLogTransfer): string
    {
        return $transactionLogTransfer->getHeidelpayResponse()->getPaymentFormUrl();
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $transactionLogTransfer
     *
     * @return string
     */
    protected function getDebitFailedRedirectUrl(HeidelpayTransactionLogTransfer $transactionLogTransfer): string
    {
        $errorCode = $transactionLogTransfer->getHeidelpayResponse()->getError()->getCode();
        $paymentFailedUrl = $this->config->getYvesCheckoutPaymentFailedUrl();

        return sprintf($paymentFailedUrl, $errorCode);
    }
}
