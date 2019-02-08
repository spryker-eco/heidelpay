<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConstants;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeGetPaymentBySalesOrderTest
 */
class HeidelpayFacadeGetPaymentBySalesOrderTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testProcessExternalPaymentResponseForSofort(): void
    {
        $salesOrder = $this->createSofortSuccessOrder();
        $paymentTransfer = $this->heidelpayFacade->getPaymentByIdSalesOrder($salesOrder->getIdSalesOrder());
        $this->assertInstanceOf(HeidelpayPaymentTransfer::class, $paymentTransfer);
        $this->assertNotNull($paymentTransfer);
        $this->assertEquals(HeidelpayTestConstants::HEIDELPAY_REFERENCE, $paymentTransfer->getIdPaymentReference());
        $this->assertEquals(PaymentTransfer::HEIDELPAY_SOFORT, $paymentTransfer->getPaymentMethod());
        $this->assertNotNull($paymentTransfer->getFkSalesOrder());
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrder
     */
    public function createSofortSuccessOrder(): SpySalesOrder
    {
        $orderBuilder = new PaymentBuilder($this->createHeidelpayFactory());
        $orderTransfer = $orderBuilder->createPayment(PaymentTransfer::HEIDELPAY_SOFORT);
        return $orderTransfer;
    }

    /**
     * @return void
     */
    public function testProcessExternalPaymentResponseForSofortForFailedPayment(): void
    {
        $paymentTransfer = $this->heidelpayFacade->getPaymentByIdSalesOrder(1000000000);
        $this->assertInstanceOf(HeidelpayPaymentTransfer::class, $paymentTransfer);
        $this->assertNotNull($paymentTransfer);
        $paymentTransferArray = $paymentTransfer->toArray();
        foreach ($paymentTransferArray as $value) {
            $this->assertNull($value);
        }
    }
}
