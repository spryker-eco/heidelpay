<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\HeidelpayBusinessFactoryMock;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\SuccessfulResponseHeidelpayBusinessFactory;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\UnsuccesfulResponseHeidelpayBusinessFactory;

/**
 * @group Functional
 * @group Spryker
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
        $salesOrder = $this->createOrder();

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());

        $orderTransfer = $this->getPaymentTransfer($heidelpayFacade, $salesOrder);

        $heidelpayFacade->capturePayment($orderTransfer);

        $transactionHandler = $this->createHeidelpayFactory()
           ->getHeidelpayQueryContainer()
           ->queryCaptureTransactionLog($salesOrder->getIdSalesOrder());
        $transaction = $transactionHandler->findOne();

        $this->assertNotNull($transaction);

        $this->assertNotEmpty($transaction->getResponseCode());
        $this->assertEquals(HeidelpayConfig::CAPTURE_TRANSACTION_STATUS_OK, $transaction->getResponseCode());
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function createOrder(): SpySalesOrder
    {
        $orderBuilder = new PaymentBuilder($this->createHeidelpayFactory());
        $orderTransfer = $orderBuilder->createPayment(PaymentTransfer::HEIDELPAY_CREDIT_CARD_SECURE);
        return $orderTransfer;
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createSuccessfulPaymentHeidelpayFactoryMock(): HeidelpayBusinessFactory
    {
        return new SuccessfulResponseHeidelpayBusinessFactory();
    }

    /**
     * @return void
     */
    public function testProcessUnsuccessfulExternalPaymentResponseForCreditCardCapture(): void
    {
        $salesOrder = $this->createOrder();

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createUnsuccessfulPaymentHeidelpayFactoryMock());

        $orderTransfer = $this->getPaymentTransfer($heidelpayFacade, $salesOrder);

        $heidelpayFacade->capturePayment($orderTransfer);

        $transactionHandler = $this->createHeidelpayFactory()
            ->getHeidelpayQueryContainer()
            ->queryCaptureTransactionLog($salesOrder->getIdSalesOrder());
        $transaction = $transactionHandler->findOne();

        $this->assertNotNull($transaction);

        $this->assertNotEmpty($transaction->getResponseCode());
        $this->assertNotEquals(HeidelpayConfig::CAPTURE_TRANSACTION_STATUS_OK, $transaction->getResponseCode());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createUnsuccessfulPaymentHeidelpayFactoryMock(): HeidelpayBusinessFactory
    {
        return new UnsuccesfulResponseHeidelpayBusinessFactory();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createHeidelpayFactory(): HeidelpayBusinessFactory
    {
        return new HeidelpayBusinessFactoryMock();
    }
}
