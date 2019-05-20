<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction;

use Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer;
use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface;

class ExternalResponseTransaction implements ExternalResponseTransactionInterface
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
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface $paymentAdapter
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function executeTransaction(
        HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer,
        PaymentWithExternalResponseInterface $paymentAdapter
    ): HeidelpayResponseTransfer {
        $heidelpayResponseTransfer = $paymentAdapter->processExternalResponse($externalResponseTransfer);
        $this->logTransaction($heidelpayResponseTransfer);

        return $heidelpayResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $externalResponseTransfer
     *
     * @return void
     */
    protected function logTransaction(HeidelpayResponseTransfer $externalResponseTransfer): void
    {
        $this->transactionLogger->logTransaction(
            HeidelpayConfig::TRANSACTION_TYPE_EXTERNAL_RESPONSE,
            new HeidelpayRequestTransfer(),
            $externalResponseTransfer
        );
    }
}
