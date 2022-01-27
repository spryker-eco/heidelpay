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
 * @group AuthorizeOnRegistrationPaymentTest
 */
class FinalizePaymentTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testProcessSuccessFinalizePayment(): void
    {
        //Arrange
        $salesOrderEntity = $this->tester->createOrder(PaymentTransfer::HEIDELPAY_EASY_CREDIT);
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrderEntity);

        //Act
        $heidelpayFacade->finalizePayment($orderTransfer);
        $transaction = $this->createHeidelpayFactory()
            ->createTransactionLogReader()
            ->findQuoteFinalizeTransactionLogByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        //Assert
        $this->testSuccessfulHeidelpayPaymentResponse($transaction);
    }

    /**
     * @return void
     */
    public function testProcessUnsuccessfulFinalizePayment(): void
    {
        //Arrange
        $salesOrderEntity = $this->tester->createOrder(PaymentTransfer::HEIDELPAY_EASY_CREDIT);
        $heidelpayFacade = $this->createFacadeWithUnsuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrderEntity);

        //Act
        $heidelpayFacade->finalizePayment($orderTransfer);
        $transaction = $this->createHeidelpayFactory()
            ->createTransactionLogReader()
            ->findQuoteFinalizeTransactionLogByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        //Assert
        $this->testUnsuccessfulHeidelpayPaymentResponse($transaction);
    }
}
