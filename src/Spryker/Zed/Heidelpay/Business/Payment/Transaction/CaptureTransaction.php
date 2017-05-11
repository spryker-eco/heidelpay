<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heidelpay\Business\Payment\Transaction;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Spryker\Shared\Heidelpay\HeidelpayConstants;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface;

class CaptureTransaction implements CaptureTransactionInterface
{

    const TRANSACTION_TYPE = HeidelpayConstants::TRANSACTION_TYPE_CAPTURE;

    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface
     */
    protected $transactionLogger;

    /**
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     */
    public function __construct(TransactionLoggerInterface $transactionLogger)
    {
        $this->transactionLogger = $transactionLogger;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $captureRequestTransfer
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface $paymentAdapter
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function executeTransaction(
        HeidelpayRequestTransfer $captureRequestTransfer,
        PaymentWithCaptureInterface $paymentAdapter
    ) {
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
    ) {
        $this->transactionLogger->logTransaction(
            static::TRANSACTION_TYPE,
            $captureRequestTransfer,
            $captureResponseTransfer
        );
    }

}
