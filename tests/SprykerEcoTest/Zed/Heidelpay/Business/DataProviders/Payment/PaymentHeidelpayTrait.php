<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
