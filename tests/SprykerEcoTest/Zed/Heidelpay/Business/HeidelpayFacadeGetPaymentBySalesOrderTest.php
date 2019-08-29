<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;

/**
 * @group Functional
 * @group SprykerEcoTest
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
        //Arrange
        $salesOrder = $this->createSofortOrder();

        //Act
        $paymentTransfer = $this->heidelpayFacade->getPaymentByIdSalesOrder($salesOrder->getIdSalesOrder());

        //Assert
        $this->assertInstanceOf(HeidelpayPaymentTransfer::class, $paymentTransfer);
        $this->assertNotNull($paymentTransfer);
        $this->assertEquals(HeidelpayTestConfig::HEIDELPAY_REFERENCE, $paymentTransfer->getIdPaymentReference());
        $this->assertEquals(PaymentTransfer::HEIDELPAY_SOFORT, $paymentTransfer->getPaymentMethod());
        $this->assertNotNull($paymentTransfer->getFkSalesOrder());
    }

    /**
     * @return void
     */
    public function testProcessExternalPaymentResponseForSofortForFailedPayment(): void
    {
        //Act
        $paymentTransfer = $this->heidelpayFacade->getPaymentByIdSalesOrder(1000000000);

        //Assert
        $this->assertInstanceOf(HeidelpayPaymentTransfer::class, $paymentTransfer);
        $this->assertNotNull($paymentTransfer);
        $paymentTransferArray = $paymentTransfer->toArray();
        foreach ($paymentTransferArray as $value) {
            $this->assertNull($value);
        }
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrder
     */
    protected function createSofortOrder(): SpySalesOrder
    {
        $orderBuilder = new PaymentBuilder($this->createHeidelpayFactory());

        return $orderBuilder->createPayment(PaymentTransfer::HEIDELPAY_SOFORT);
    }
}
