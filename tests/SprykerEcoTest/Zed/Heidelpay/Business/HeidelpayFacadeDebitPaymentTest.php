<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Codeception\TestCase\Test;

use Generated\Shared\Transfer\HeidelpayResponseTransfer;

use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Zed\Sales\Business\SalesFacade;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesBridge;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;

use SprykerEcoTest\Zed\Heidelpay\Business\Mock\SuccessfulResponseHeidelpayBusinessFactory;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\UnsuccesfulResponseHeidelpayBusinessFactory;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeDebitPaymentTest
 */
class HeidelpayFacadeDebitPaymentTest extends Test
{

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade
     */
    protected $heidelpayFacade;

    /**
     * @var
     */
    protected $heidelpayFactory;


    /**
     * @var
     */
    protected $heidelpayToSales;

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();



        $this->heidelpayToSales = new HeidelpayToSalesBridge(new SalesFacade());

        $this->getModule('\\' . ConfigHelper::class)
            ->setConfig(HeidelpayConstants::CONFIG_ENCRYPTION_KEY, 'encryption_key');
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createHeidelpayFactory()
    {
        return new HeidelpayBusinessFactory();
    }

    /**
     * @return void
     */
    public function testProcessSuccessfulExternalPaymentResponseForSofort()
    {

       $salesOrder = $this->_createSuccessOrder();

       $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());

       $paymentTransfer = $heidelpayFacade->getPaymentByIdSalesOrder($salesOrder->getIdSalesOrder());
       $orderTransfer = $this->heidelpayToSales->getOrderByIdSalesOrder($salesOrder->getIdSalesOrder());
       $orderTransfer->setHeidelpayPayment($paymentTransfer);

       $heidelpayFacade->debitPayment($orderTransfer);

       $transaction = $this->createHeidelpayFactory()->createTransactionLogReader()
           ->findOrderAuthorizeTransactionLogByIdSalesOrder($salesOrder->getIdSalesOrder());

       $this->assertNotNull($transaction->getHeidelpayResponse());
       $this->assertInstanceOf(HeidelpayResponseTransfer::class, $transaction->getHeidelpayResponse());
       $this->assertFalse($transaction->getHeidelpayResponse()->getIsError());
       $this->assertTrue($transaction->getHeidelpayResponse()->getIsSuccess());
       $this->assertEquals(
           HeidelpayTestConstants::HEIDELPAY_SUCCESS_RESPONSE,
           $transaction->getHeidelpayResponse()->getResultCode()
       );
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function _createSuccessOrder()
    {
        $orderBuilder = new PaymentBuilder($this->createHeidelpayFactory());
        $orderTransfer = $orderBuilder->createPayment(PaymentTransfer::HEIDELPAY_PAYPAL_DEBIT);
        return $orderTransfer;
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createSuccessfulPaymentHeidelpayFactoryMock()
    {
        return new HeidelpayBusinessFactory();
    }

    /**
     * @return void
     */
    public function xtestProcessUnccessfulExternalPaymentResponseForSofort()
    {
        $salesOrder = $this->_createSuccessOrder();

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createUnsuccessfulPaymentHeidelpayFactoryMock());

        $paymentTransfer = $heidelpayFacade->getPaymentByIdSalesOrder($salesOrder->getIdSalesOrder());
        $orderTransfer = $this->heidelpayToSales->getOrderByIdSalesOrder($salesOrder->getIdSalesOrder());
        $orderTransfer->setHeidelpayPayment($paymentTransfer);

        $heidelpayFacade->authorizePayment($orderTransfer);

        $transaction = $this->createHeidelpayFactory()->createTransactionLogReader()
            ->findOrderAuthorizeTransactionLogByIdSalesOrder($salesOrder->getIdSalesOrder());

        $this->assertNotNull($transaction->getHeidelpayResponse());
        $this->assertInstanceOf(HeidelpayResponseTransfer::class, $transaction->getHeidelpayResponse());
        $this->assertTrue($transaction->getHeidelpayResponse()->getIsError());
        $this->assertFalse($transaction->getHeidelpayResponse()->getIsSuccess());

    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createUnsuccessfulPaymentHeidelpayFactoryMock()
    {
        return new HeidelpayBusinessFactory();
    }

}