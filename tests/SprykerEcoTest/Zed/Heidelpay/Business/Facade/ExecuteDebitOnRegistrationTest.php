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
 * @group ExecuteDebitOnRegistrationTest
 */
class ExecuteDebitOnRegistrationTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testSuccessfulExecuteDebitOnRegistration(): void
    {
        // Arrange
        $salesOrderEntity = $this->tester->createOrder(PaymentTransfer::HEIDELPAY_DIRECT_DEBIT);
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrderEntity);

        // Act
        $heidelpayFacade->executeDebitOnRegistration($orderTransfer);

        $heidelpayTransactionLogCriteriaTransfer = (new PaymentHeidelpayTransactionLogCriteriaTransfer())
            ->setIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->setTransactionType(HeidelpayConfig::TRANSACTION_TYPE_DEBIT_ON_REGISTRATION)
            ->setResponseCode(HeidelpayTestConfig::HEIDELPAY_SUCCESS_RESPONSE);
        $paymentHeidelpayTransactionLogEntity = $this->tester->findPaymentHeidelpayTransactionLog($heidelpayTransactionLogCriteriaTransfer);

        // Assert
        $this->assertNotNull($paymentHeidelpayTransactionLogEntity);
        $this->assertNotEmpty($paymentHeidelpayTransactionLogEntity->getIdTransactionUnique());
        $this->assertNotEmpty($paymentHeidelpayTransactionLogEntity->getResponsePayload());
        $this->assertNotEmpty($paymentHeidelpayTransactionLogEntity->getRequestPayload());
    }
}
