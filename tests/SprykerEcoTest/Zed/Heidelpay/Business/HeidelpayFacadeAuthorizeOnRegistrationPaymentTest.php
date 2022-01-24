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
 * @group HeidelpayFacadeAuthorizeOnRegistrationPaymentTest
 */
class HeidelpayFacadeAuthorizeOnRegistrationPaymentTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testProcessSuccessAuthorizeOnRegistrationPayment(): void
    {
        //Arrange
        $salesOrder = $this->createOrder();
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrder);

        //Act
        $heidelpayFacade->authorizeOnRegistrationPayment($orderTransfer);
        $transaction = $this->createHeidelpayFactory()
            ->createTransactionLogReader()
            ->findOrderAuthorizeOnRegistrationTransactionLogByIdSalesOrder($salesOrder->getIdSalesOrder());

        //Assert
        $this->testSuccessfulHeidelpayPaymentResponse($transaction);
    }

    /**
     * @return void
     */
    public function testProcessUnsuccessfulAuthorizeOnRegistrationPayment(): void
    {
        //Arrange
        $salesOrder = $this->createOrder();
        $heidelpayFacade = $this->createFacadeWithUnsuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrder);

        //Act
        $heidelpayFacade->authorizeOnRegistrationPayment($orderTransfer);
        $transaction = $this->createHeidelpayFactory()
            ->createTransactionLogReader()
            ->findOrderAuthorizeOnRegistrationTransactionLogByIdSalesOrder($salesOrder->getIdSalesOrder());

        //Assert
        $this->testUnsuccessfulHeidelpayPaymentResponse($transaction);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createOrder(): SpySalesOrder
    {
        $paymentBuilder = new PaymentBuilder();

        return $paymentBuilder->createPayment(PaymentTransfer::HEIDELPAY_EASY_CREDIT);
    }
}
