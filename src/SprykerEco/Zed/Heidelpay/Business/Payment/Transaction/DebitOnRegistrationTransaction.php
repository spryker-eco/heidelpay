<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitOnRegistrationInterface;

class DebitOnRegistrationTransaction implements DebitOnRegistrationTransactionInterface
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
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $debitOnRegistrationRequestTransfer
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitOnRegistrationInterface $paymentAdapter
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function executeTransaction(
        HeidelpayRequestTransfer $debitOnRegistrationRequestTransfer,
        PaymentWithDebitOnRegistrationInterface $paymentAdapter
    ): HeidelpayResponseTransfer {
        $debitOnRegistrationResponseTransfer = $paymentAdapter->debitOnRegistration($debitOnRegistrationRequestTransfer);
        $this->transactionLogger->logTransaction(
            HeidelpayConfig::TRANSACTION_TYPE_DEBIT_ON_REGISTRATION,
            $debitOnRegistrationRequestTransfer,
            $debitOnRegistrationResponseTransfer
        );

        return $debitOnRegistrationResponseTransfer;
    }
}
