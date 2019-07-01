<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
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
    protected function getOrderTransfer(HeidelpayFacade $heidelpayFacade, SpySalesOrder $salesOrder): OrderTransfer
    {
        $paymentTransfer = $heidelpayFacade->getPaymentByIdSalesOrder($salesOrder->getIdSalesOrder());
        $orderTransfer = $this->heidelpayToSales->getOrderByIdSalesOrder($salesOrder->getIdSalesOrder());
        $orderTransfer->setHeidelpayPayment($paymentTransfer);
        
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setCreatedAt('2019-01-01 00:00:00');
        
        $orderTransfer->setCustomer($customerTransfer);

        return $orderTransfer;
    }
}
