<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface;

class DebitTransaction implements DebitTransactionInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface
     */
    protected $transactionLogger;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     */
    public function __construct(TransactionLoggerInterface $transactionLogger)
    {
        $this->transactionLogger = $transactionLogger;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $debitRequestTransfer
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface $paymentAdapter
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function executeTransaction(
        HeidelpayRequestTransfer $debitRequestTransfer,
        PaymentWithDebitInterface $paymentAdapter
    ) {
        $debitResponseTransfer = $paymentAdapter->debit($debitRequestTransfer);
        $this->logTransaction($debitRequestTransfer, $debitResponseTransfer);

        return $debitResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $debitRequestTransfer
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $debitResponseTransfer
     *
     * @return void
     */
    protected function logTransaction(
        HeidelpayRequestTransfer $debitRequestTransfer,
        HeidelpayResponseTransfer $debitResponseTransfer
    ) {
        $this->transactionLogger->logTransaction(
            HeidelpayConfig::TRANSACTION_TYPE_DEBIT,
            $debitRequestTransfer,
            $debitResponseTransfer
        );
    }
}
