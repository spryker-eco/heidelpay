<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Codeception\TestCase\Test;

use Generated\Shared\Transfer\PaymentTransfer;
use Propel\Runtime\Propel;
use Spryker\Zed\Sales\Business\SalesFacade;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesBridge;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;

use SprykerEcoTest\Zed\Heidelpay\Business\Mock\HeidelpayBusinessFactoryMock;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\SuccessfulResponseHeidelpayBusinessFactory;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\UnsuccesfulResponseHeidelpayBusinessFactory;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

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
    public function testProcessSuccessfulExternalPaymentResponseForCreditCardCapture()
    {
        $salesOrder = $this->_createOrder();

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
    public function _createOrder()
    {
        $orderBuilder = new PaymentBuilder($this->createHeidelpayFactory());
        $orderTransfer = $orderBuilder->createPayment(PaymentTransfer::HEIDELPAY_CREDIT_CARD_SECURE);
        return $orderTransfer;
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createSuccessfulPaymentHeidelpayFactoryMock()
    {
        return new SuccessfulResponseHeidelpayBusinessFactory();
    }

    /**
     * @return void
     */
    public function testProcessUnsuccessfulExternalPaymentResponseForCreditCardCapture()
    {
        $salesOrder = $this->_createOrder();

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
    protected function createUnsuccessfulPaymentHeidelpayFactoryMock()
    {
        return new UnsuccesfulResponseHeidelpayBusinessFactory();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createHeidelpayFactory()
    {
        return new HeidelpayBusinessFactoryMock();
    }
}
