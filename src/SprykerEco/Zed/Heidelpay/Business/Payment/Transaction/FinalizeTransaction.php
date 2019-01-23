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
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithFinalizeInterface;

class FinalizeTransaction implements FinalizeTransactionInterface
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
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $finalizeRequestTransfer
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithFinalizeInterface $paymentAdapter
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function executeTransaction(
        HeidelpayRequestTransfer $finalizeRequestTransfer,
        PaymentWithFinalizeInterface $paymentAdapter
    ) {
        $finalizeResponseTransfer = $paymentAdapter->finalize($finalizeRequestTransfer);
        $this->logTransaction($finalizeRequestTransfer, $finalizeResponseTransfer);

        return $finalizeResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $finalizeRequestTransfer
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $finalizeResponseTransfer
     *
     * @return void
     */
    protected function logTransaction(
        HeidelpayRequestTransfer $finalizeRequestTransfer,
        HeidelpayResponseTransfer $finalizeResponseTransfer
    ) {
        $this->transactionLogger->logTransaction(
            HeidelpayConfig::TRANSACTION_TYPE_FINALIZE,
            $finalizeRequestTransfer,
            $finalizeResponseTransfer
        );
    }
}
