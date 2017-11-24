<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment;

trait PaymentHeidelpayTransferBuilderTrait
{

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade $heidelpayFacade
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrder
     *
     * @return mixed
     */
    protected function getPaymentTransfer($heidelpayFacade, $salesOrder)
    {
        $paymentTransfer = $heidelpayFacade->getPaymentByIdSalesOrder($salesOrder->getIdSalesOrder());
        $orderTransfer = $this->heidelpayToSales->getOrderByIdSalesOrder($salesOrder->getIdSalesOrder());
        $orderTransfer->setHeidelpayPayment($paymentTransfer);
        return $orderTransfer;
    }

}
