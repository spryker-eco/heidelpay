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
 * @group DebitPaymentTest
 */
class DebitPaymentTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testProcessSuccessfulExternalPaymentResponseForPaypalDebit(): void
    {
        //Arrange
        $salesOrderEntity = $this->tester->createOrder(PaymentTransfer::HEIDELPAY_PAYPAL_DEBIT);
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrderEntity);

        //Act
        $heidelpayFacade->debitPayment($orderTransfer);
        $transaction = $this->createHeidelpayFactory()
            ->createTransactionLogReader()
            ->findOrderDebitTransactionLog($salesOrderEntity->getIdSalesOrder());

        //Assert
        $this->testSuccessfulHeidelpayPaymentResponse($transaction);
    }

    /**
     * @return void
     */
    public function testProcessUnsuccessfulExternalPaymentResponseForPaypalDebit(): void
    {
        //Arrange
        $salesOrderEntity = $this->tester->createOrder(PaymentTransfer::HEIDELPAY_PAYPAL_DEBIT);
        $heidelpayFacade = $this->createFacadeWithUnsuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrderEntity);

        //Act
        $heidelpayFacade->debitPayment($orderTransfer);
        $transaction = $this->createHeidelpayFactory()
            ->createTransactionLogReader()
            ->findOrderDebitTransactionLog($salesOrderEntity->getIdSalesOrder());

        //Assert
        $this->testUnsuccessfulHeidelpayPaymentResponse($transaction);
    }
}
