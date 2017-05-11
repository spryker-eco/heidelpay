<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heidelpay\Business\Payment;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface;

class PaypalDebit extends BaseHeidelpayPaymentMethod implements PaymentWithPostSaveOrderInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function postSaveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $redirectUrl = $this->getCheckoutRedirectUrlFromDebitTransactionLog(
            $checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder()
        );

        $this->setExternalRedirect($redirectUrl, $checkoutResponseTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string|null
     */
    protected function getCheckoutRedirectUrlFromDebitTransactionLog($idSalesOrder)
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
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer|null
     */
    protected function findOrderDebitTransactionLog($idSalesOrder)
    {
        return $this->transactionLogManager->findOrderDebitTransactionLog($idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $debitTransactionLogTransfer
     *
     * @return bool
     */
    protected function isDebitTransactionSentSuccessfully(HeidelpayTransactionLogTransfer $debitTransactionLogTransfer)
    {
        return $debitTransactionLogTransfer->getHeidelpayResponse()->getIsSuccess();
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $transactionLogTransfer
     *
     * @return string
     */
    protected function getDebitRedirectUrl(HeidelpayTransactionLogTransfer $transactionLogTransfer)
    {
        return $transactionLogTransfer->getHeidelpayResponse()->getPaymentFormUrl();
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $transactionLogTransfer
     *
     * @return string
     */
    protected function getDebitFailedRedirectUrl(HeidelpayTransactionLogTransfer $transactionLogTransfer)
    {
        $errorCode = $transactionLogTransfer->getHeidelpayResponse()->getError()->getCode();
        $paymentFailedUrl = $this->config->getYvesCheckoutPaymentFailedUrl();

        return sprintf($paymentFailedUrl, $errorCode);
    }

}
