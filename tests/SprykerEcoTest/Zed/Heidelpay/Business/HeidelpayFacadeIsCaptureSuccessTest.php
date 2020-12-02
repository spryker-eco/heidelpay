<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainer;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Transaction\CaptureTransactionTrait;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeIsCaptureSuccessTest
 */
class HeidelpayFacadeIsCaptureSuccessTest extends HeidelpayPaymentTest
{
    use CaptureTransactionTrait;

    /**
     * @return void
     */
    public function testCheckShouldReturnFalseWhenCaptureTransactionLogIsEmpty(): void
    {
        // Arrange
        $salesOrder = $this->createOrder();
        $orderItemEntity = $salesOrder->getItems()[0];
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();

        // Act
        $result = $heidelpayFacade->isCaptureSuccessful($orderItemEntity);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCheckShouldReturnFalseWhenCaptureTransactionLogContainsOnlyFailedRows()
    {
        // Arrange
        $salesOrder = $this->createOrder();
        $orderItemEntity = $salesOrder->getItems()[0];
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();
        $this->createCaptureTransactionLog($salesOrder->getIdSalesOrder(), false);

        // Act
        $result = $heidelpayFacade->isCaptureSuccessful($orderItemEntity);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCheckShouldReturnTrueWhenCaptureTransactionLogContainsSuccessRow()
    {
        // Arrange
        $salesOrder = $this->createOrder();
        $orderItemEntity = $salesOrder->getItems()[0];
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();
        $idSalesOrder = $salesOrder->getIdSalesOrder();
        $this->createCaptureTransactionLog($idSalesOrder, false);
        $this->createCaptureTransactionLog($idSalesOrder, true);
        $this->createCaptureTransactionLog($idSalesOrder, false);

        // Act
        $result = $heidelpayFacade->isCaptureSuccessful($orderItemEntity);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createOrder(): SpySalesOrder
    {
        $orderBuilder = new PaymentBuilder($this->createHeidelpayFactory());

        return $orderBuilder->createPayment(PaymentTransfer::HEIDELPAY_CREDIT_CARD_SECURE);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface
     */
    protected function createQueryContainer(): HeidelpayQueryContainerInterface
    {
        return new HeidelpayQueryContainer();
    }
}
