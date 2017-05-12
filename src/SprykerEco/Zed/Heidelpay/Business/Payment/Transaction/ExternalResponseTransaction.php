<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction;

use Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Spryker\Shared\Heidelpay\HeidelpayConstants;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface;

class ExternalResponseTransaction implements ExternalResponseTransactionInterface
{

    const TRANSACTION_TYPE = HeidelpayConstants::TRANSACTION_TYPE_EXTERNAL_RESPONSE;

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
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface $paymentAdapter
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function executeTransaction(
        HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer,
        PaymentWithExternalResponseInterface $paymentAdapter
    ) {
        $heidelpayResponseTransfer = $paymentAdapter->processExternalResponse($externalResponseTransfer);
        $this->logTransaction($heidelpayResponseTransfer);

        return $heidelpayResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $externalResponseTransfer
     *
     * @return void
     */
    protected function logTransaction(
        HeidelpayResponseTransfer $externalResponseTransfer
    ) {
        $this->transactionLogger->logTransaction(
            static::TRANSACTION_TYPE,
            null,
            $externalResponseTransfer
        );
    }

}
