<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;

/**
 * @group Functional
 * @group SprykerEcoTest
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
    public function testProcessSuccessReservationPayment(): void
    {
        //Arrange
        $salesOrder = $this->createOrder();
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrder);

        //Act
        $heidelpayFacade->executePaymentReservation($orderTransfer);
        $transaction = $this->createHeidelpayFactory()
            ->createTransactionLogReader()
            ->findQuoteReservationTransactionLogByIdSalesOrder($salesOrder->getIdSalesOrder());

        //Assert
        $this->testSuccessfulHeidelpayPaymentResponse($transaction);
    }

    /**
     * @return void
     */
    public function testProcessUnsuccessfulReservationPayment(): void
    {
        //Arrange
        $salesOrder = $this->createOrder();
        $heidelpayFacade = $this->createFacadeWithUnsuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrder);

        //Act
        $heidelpayFacade->executePaymentReservation($orderTransfer);
        $transaction = $this->createHeidelpayFactory()
            ->createTransactionLogReader()
            ->findQuoteReservationTransactionLogByIdSalesOrder($salesOrder->getIdSalesOrder());

        //Assert
        $this->testUnsuccessfulHeidelpayPaymentResponse($transaction);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createOrder(): SpySalesOrder
    {
        $orderBuilder = new PaymentBuilder($this->createHeidelpayFactory());

        return $orderBuilder->createPayment(PaymentTransfer::HEIDELPAY_EASY_CREDIT);
    }
}
