<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\PaymentHeidelpayTransactionLogCriteriaTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group Facade
 * @group ExecuteRefundTest
 */
class ExecuteRefundTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testExecuteRefund(): void
    {
        //Arrange
        $salesOrderEntity = $this->tester->createOrder(PaymentTransfer::HEIDELPAY_DIRECT_DEBIT);
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrderEntity);

        //Act
        $heidelpayFacade->executeRefund($orderTransfer);

        //Assert
        $heidelpayTransactionLogCriteriaTransfer = (new PaymentHeidelpayTransactionLogCriteriaTransfer())
            ->setIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->setTransactionType(HeidelpayConfig::TRANSACTION_TYPE_REFUND)
            ->setResponseCode(HeidelpayTestConfig::HEIDELPAY_SUCCESS_RESPONSE);
        $paymentHeidelpayTransactionLogEntity = $this->tester->findPaymentHeidelpayTransactionLog($heidelpayTransactionLogCriteriaTransfer);

        $this->assertNotNull($paymentHeidelpayTransactionLogEntity);
        $this->assertNotEmpty($paymentHeidelpayTransactionLogEntity->getIdTransactionUnique());
        $this->assertNotEmpty($paymentHeidelpayTransactionLogEntity->getResponsePayload());
        $this->assertNotEmpty($paymentHeidelpayTransactionLogEntity->getRequestPayload());
    }
}
