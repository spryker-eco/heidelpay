<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment;

use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog;

trait PaymentHeidelpayTransactionLogTrait
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param string $responseCode
     * @param string $transactionType
     *
     * @return void
     */
    protected function createHeidelpayPaymentTransactionLogEntity(
        $salesOrderEntity,
        string $responseCode,
        string $transactionType
    ) {
        $payment = new SpyPaymentHeidelpayTransactionLog();
        $payment->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $payment->setTransactionType($transactionType);
        $payment->setResponseCode($responseCode);
        $payment->save();
    }
}
