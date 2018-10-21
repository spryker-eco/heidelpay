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
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface;

class CaptureTransaction implements CaptureTransactionInterface
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
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $captureRequestTransfer
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface $paymentAdapter
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function executeTransaction(
        HeidelpayRequestTransfer $captureRequestTransfer,
        PaymentWithCaptureInterface $paymentAdapter
    ): HeidelpayResponseTransfer {
        $captureResponseTransfer = $paymentAdapter->capture($captureRequestTransfer);
        $this->logTransaction($captureRequestTransfer, $captureResponseTransfer);

        return $captureResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $captureRequestTransfer
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $captureResponseTransfer
     *
     * @return void
     */
    protected function logTransaction(
        HeidelpayRequestTransfer $captureRequestTransfer,
        HeidelpayResponseTransfer $captureResponseTransfer
    ): void {
        $this->transactionLogger->logTransaction(
            HeidelpayConfig::TRANSACTION_TYPE_CAPTURE,
            $captureRequestTransfer,
            $captureResponseTransfer
        );
    }
}
