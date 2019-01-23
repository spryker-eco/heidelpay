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
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithInitializeInterface;

class InitializeTransaction implements InitializeTransactionInterface
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
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $initializeRequestTransfer
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithInitializeInterface $paymentAdapter
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function executeTransaction(
        HeidelpayRequestTransfer $initializeRequestTransfer,
        PaymentWithInitializeInterface $paymentAdapter
    ) {
        $initializeResponseTransfer = $paymentAdapter->initialize($initializeRequestTransfer);
        $this->logTransaction($initializeRequestTransfer, $initializeResponseTransfer);

        return $initializeResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $initializeRequestTransfer
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $initializeResponseTransfer
     *
     * @return void
     */
    protected function logTransaction(
        HeidelpayRequestTransfer $initializeRequestTransfer,
        HeidelpayResponseTransfer $initializeResponseTransfer
    ) {
        $this->transactionLogger->logTransaction(
            HeidelpayConfig::TRANSACTION_TYPE_INITIALIZE,
            $initializeRequestTransfer,
            $initializeResponseTransfer
        );
    }
}
