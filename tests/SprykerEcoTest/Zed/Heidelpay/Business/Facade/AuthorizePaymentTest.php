<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\PaymentTransfer;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group Facade
 * @group AuthorizePaymentTest
 */
class AuthorizePaymentTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testProcessSuccessfulExternalPaymentResponseForSofort(): void
    {
        //Arrange
        $salesOrderEntity = $this->tester->createOrder(PaymentTransfer::HEIDELPAY_SOFORT);
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrderEntity);

        //Act
        $heidelpayFacade->authorizePayment($orderTransfer);
        $transaction = $this->createHeidelpayFactory()
            ->createTransactionLogReader()
            ->findOrderAuthorizeTransactionLogByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        //Assert
        $this->testSuccessfulHeidelpayPaymentResponse($transaction);
    }

    /**
     * @return void
     */
    public function testProcessUnsuccessfulExternalPaymentResponseForSofort(): void
    {
        //Arrange
        $salesOrderEntity = $this->tester->createOrder(PaymentTransfer::HEIDELPAY_SOFORT);
        $heidelpayFacade = $this->createFacadeWithUnsuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrderEntity);

        //Act
        $heidelpayFacade->authorizePayment($orderTransfer);
        $transaction = $this->createHeidelpayFactory()
            ->createTransactionLogReader()
            ->findOrderAuthorizeTransactionLogByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        //Assert
        $this->testUnsuccessfulHeidelpayPaymentResponse($transaction);
    }
}
