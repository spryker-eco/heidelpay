<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainer;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeCapturePaymentTest
 */
class HeidelpayFacadeCapturePaymentTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testProcessSuccessfulExternalPaymentResponseForCreditCardCapture(): void
    {
        //Arrange
        $salesOrder = $this->createOrder();
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrder);

        //Act
        $heidelpayFacade->capturePayment($orderTransfer);
        $transaction = $this->createQueryContainer()
            ->queryCaptureTransactionLog($salesOrder->getIdSalesOrder())
            ->findOne();

        //Assert
        $this->assertNotNull($transaction);
        $this->assertNotEmpty($transaction->getResponseCode());
        $this->assertEquals(HeidelpayConfig::CAPTURE_TRANSACTION_STATUS_OK, $transaction->getResponseCode());
    }

    /**
     * @return void
     */
    public function testProcessUnsuccessfulExternalPaymentResponseForCreditCardCapture(): void
    {
        //Arrange
        $salesOrder = $this->createOrder();
        $heidelpayFacade = $this->createFacadeWithUnsuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrder);

        //Act
        $heidelpayFacade->capturePayment($orderTransfer);
        $transaction = $this->createQueryContainer()
            ->queryCaptureTransactionLog($salesOrder->getIdSalesOrder())
            ->findOne();

        //Assert
        $this->assertNotNull($transaction);
        $this->assertNotEmpty($transaction->getResponseCode());
        $this->assertNotEquals(HeidelpayConfig::CAPTURE_TRANSACTION_STATUS_OK, $transaction->getResponseCode());
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createOrder(): SpySalesOrder
    {
        $orderBuilder = new PaymentBuilder();

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
