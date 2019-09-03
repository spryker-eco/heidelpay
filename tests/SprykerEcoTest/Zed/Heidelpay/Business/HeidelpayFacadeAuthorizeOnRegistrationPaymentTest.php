<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\SuccessfulResponseHeidelpayBusinessFactory;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\UnsuccesfulResponseHeidelpayBusinessFactory;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeAuthorizeOnRegistrationPaymentTest
 */
class HeidelpayFacadeAuthorizeOnRegistrationPaymentTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testProcessSuccessAuthorizeOnRegistrationPayment()
    {
        $salesOrder = $this->createSuccessOrder();

        $heidelpayFacade = (new HeidelpayFacade())->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrder);
        $heidelpayFacade->authorizeOnRegistrationPayment($orderTransfer);

        $transaction = $this->createHeidelpayFactory()->createTransactionLogReader()
            ->findOrderAuthorizeOnRegistrationTransactionLogByIdSalesOrder($salesOrder->getIdSalesOrder());

        $this->testSuccessfulHeidelpayPaymentResponse($transaction);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function createSuccessOrder()
    {
        $orderBuilder = new PaymentBuilder($this->createHeidelpayFactory());
        $orderTransfer = $orderBuilder->createPayment(PaymentTransfer::HEIDELPAY_EASY_CREDIT);

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
    public function testProcessUnsuccessfulAuthorizeOnRegistrationPayment()
    {
        $salesOrder = $this->createSuccessOrder();

        $heidelpayFacade = (new HeidelpayFacade())->setFactory($this->createUnsuccessfulPaymentHeidelpayFactoryMock());
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrder);
        $heidelpayFacade->authorizeOnRegistrationPayment($orderTransfer);

        $transaction = $this->createHeidelpayFactory()->createTransactionLogReader()
            ->findOrderAuthorizeOnRegistrationTransactionLogByIdSalesOrder($salesOrder->getIdSalesOrder());

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
