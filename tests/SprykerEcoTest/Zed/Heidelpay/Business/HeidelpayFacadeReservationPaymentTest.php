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
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeReservationPaymentTest
 */
class HeidelpayFacadeReservationPaymentTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testProcessSuccessReservationPayment()
    {
        $salesOrder = $this->createSuccessOrder();

        $heidelpayFacade = (new HeidelpayFacade())->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());
        $orderTransfer = $this->getPaymentTransfer($heidelpayFacade, $salesOrder);
        $heidelpayFacade->reservationPayment($orderTransfer);

        $transaction = $this->createHeidelpayFactory()
            ->createTransactionLogReader()
            ->findQuoteReservationTransactionLogByIdSalesOrder($salesOrder->getIdSalesOrder());

        $this->testSuccessfulHeidelpayPaymentResponse($transaction);
    }

    /**
     * @return void
     */
    public function testProcessUnsuccessfulReservationPayment()
    {
        $salesOrder = $this->createSuccessOrder();

        $heidelpayFacade = (new HeidelpayFacade())->setFactory($this->createUnsuccessfulPaymentHeidelpayFactoryMock());
        $orderTransfer = $this->getPaymentTransfer($heidelpayFacade, $salesOrder);
        $heidelpayFacade->reservationPayment($orderTransfer);

        $transaction = $this->createHeidelpayFactory()
            ->createTransactionLogReader()
            ->findQuoteReservationTransactionLogByIdSalesOrder($salesOrder->getIdSalesOrder());

        $this->testUnsuccessfulHeidelpayPaymentResponse($transaction);
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
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createUnsuccessfulPaymentHeidelpayFactoryMock()
    {
        return new UnsuccesfulResponseHeidelpayBusinessFactory();
    }
}
