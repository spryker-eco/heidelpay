<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainer;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group Facade
 * @group CapturePaymentTest
 */
class CapturePaymentTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testProcessSuccessfulExternalPaymentResponseForCreditCardCapture(): void
    {
        //Arrange
        $salesOrderEntity = $this->tester->createOrder(PaymentTransfer::HEIDELPAY_CREDIT_CARD_SECURE);
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrderEntity);

        //Act
        $heidelpayFacade->capturePayment($orderTransfer);
        $transaction = $this->createQueryContainer()
            ->queryCaptureTransactionLog($salesOrderEntity->getIdSalesOrder())
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
        $salesOrderEntity = $this->tester->createOrder(PaymentTransfer::HEIDELPAY_CREDIT_CARD_SECURE);
        $heidelpayFacade = $this->createFacadeWithUnsuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrderEntity);

        //Act
        $heidelpayFacade->capturePayment($orderTransfer);
        $transaction = $this->createQueryContainer()
            ->queryCaptureTransactionLog($salesOrderEntity->getIdSalesOrder())
            ->findOne();

        //Assert
        $this->assertNotNull($transaction);
        $this->assertNotEmpty($transaction->getResponseCode());
        $this->assertNotEquals(HeidelpayConfig::CAPTURE_TRANSACTION_STATUS_OK, $transaction->getResponseCode());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface
     */
    protected function createQueryContainer(): HeidelpayQueryContainerInterface
    {
        return new HeidelpayQueryContainer();
    }
}
