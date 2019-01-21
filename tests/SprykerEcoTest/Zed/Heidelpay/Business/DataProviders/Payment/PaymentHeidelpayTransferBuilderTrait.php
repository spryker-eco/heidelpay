<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;

trait PaymentHeidelpayTransferBuilderTrait
{
    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade $heidelpayFacade
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrder
     *
     * @return mixed
     */
    protected function getPaymentTransfer(HeidelpayFacade $heidelpayFacade, SpySalesOrder $salesOrder)
    {
        $paymentTransfer = $heidelpayFacade->getPaymentByIdSalesOrder($salesOrder->getIdSalesOrder());
        $orderTransfer = $this->heidelpayToSales->getOrderByIdSalesOrder($salesOrder->getIdSalesOrder());
        $orderTransfer->setHeidelpayPayment($paymentTransfer);
        return $orderTransfer;
    }
}
