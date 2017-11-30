<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\PaymentTransfer;

use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;

use SprykerEcoTest\Zed\Heidelpay\Business\Mock\SuccessfulResponseHeidelpayBusinessFactory;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\UnsuccesfulResponseHeidelpayBusinessFactory;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeAuthorizePaymentTest
 */
class HeidelpayFacadeAuthorizePaymentTest extends HeidelpayPaymentTest
{

    /**
     * @return void
     */
    public function testProcessSuccessfulExternalPaymentResponseForSofort()
    {
        $salesOrder = $this->_createSuccessOrder();

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());

        $orderTransfer = $this->getPaymentTransfer($heidelpayFacade, $salesOrder);

        $heidelpayFacade->authorizePayment($orderTransfer);

        $transaction = $this->createHeidelpayFactory()->createTransactionLogReader()
           ->findOrderAuthorizeTransactionLogByIdSalesOrder($salesOrder->getIdSalesOrder());

        $this->testSuccessfulHeidelpayPaymentResponse($transaction);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function _createSuccessOrder()
    {
        $orderBuilder = new PaymentBuilder($this->createHeidelpayFactory());
        $orderTransfer = $orderBuilder->createPayment(PaymentTransfer::HEIDELPAY_SOFORT);
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
    public function testProcessUnsuccessfulExternalPaymentResponseForSofort()
    {
        $salesOrder = $this->_createSuccessOrder();

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createUnsuccessfulPaymentHeidelpayFactoryMock());

        $orderTransfer = $this->getPaymentTransfer($heidelpayFacade, $salesOrder);

        $heidelpayFacade->authorizePayment($orderTransfer);

        $transaction = $this->createHeidelpayFactory()->createTransactionLogReader()
            ->findOrderAuthorizeTransactionLogByIdSalesOrder($salesOrder->getIdSalesOrder());

        $this->testUnsuccessfulHeidelpayPaymentResponse($transaction);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createUnsuccessfulPaymentHeidelpayFactoryMock()
    {
        return new UnsuccesfulResponseHeidelpayBusinessFactory();
    }

}
