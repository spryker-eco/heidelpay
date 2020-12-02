<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Transaction;

use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;

trait CaptureTransactionTrait
{
    /**
     * @param int $idSalesOrder
     * @param bool $isTransactionSuccess
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog
     */
    public function createCaptureTransactionLog(int $idSalesOrder, bool $isTransactionSuccess = false): SpyPaymentHeidelpayTransactionLog
    {
        $debitTransaction = new SpyPaymentHeidelpayTransactionLog();
        $debitTransaction
            ->setFkSalesOrder($idSalesOrder)
            ->setIdTransactionUnique('unique id')
            ->setTransactionType(HeidelpayConfig::TRANSACTION_TYPE_CAPTURE)
            ->setRequestPayload('{}')
            ->setResponsePayload('{}');

        $debitTransaction = $isTransactionSuccess ?
            $this->addSuccessCaptureData($debitTransaction) :
            $this->addFailedCaptureData($debitTransaction);

        $debitTransaction->save();

        return $debitTransaction;
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog $debitTransaction
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog
     */
    protected function addSuccessCaptureData(SpyPaymentHeidelpayTransactionLog $debitTransaction): SpyPaymentHeidelpayTransactionLog
    {
        $debitTransaction
            ->setResponseCode(HeidelpayConfig::CAPTURE_TRANSACTION_STATUS_OK)
            ->setProcessingCode('CC.CP.90.00');

        return $debitTransaction;
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog $debitTransaction
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog
     */
    protected function addFailedCaptureData(SpyPaymentHeidelpayTransactionLog $debitTransaction): SpyPaymentHeidelpayTransactionLog
    {
        $debitTransaction
            ->setResponseCode(HeidelpayConfig::CAPTURE_TRANSACTION_STATUS_FAILED)
            ->setProcessingCode('CC.CP.70.30');

        return $debitTransaction;
    }
}
