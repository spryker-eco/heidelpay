<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment;

use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

trait PaymentHeidelpayTrait
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param string $idPaymentReference
     * @param string $paymentMethod
     *
     * @return void
     */
    protected function createHeidelpayPaymentEntity(
        SpySalesOrder $salesOrderEntity,
        string $idPaymentReference,
        string $paymentMethod
    ): void {
        $payment = new SpyPaymentHeidelpay();
        $payment->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());

        if ($idPaymentReference !== '') {
            $payment->setIdPaymentReference($idPaymentReference);
        }
        $payment->setPaymentMethod($paymentMethod);
        $payment->save();
    }
}
