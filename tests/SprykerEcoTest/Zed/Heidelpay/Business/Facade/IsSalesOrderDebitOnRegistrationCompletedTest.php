<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
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
class IsSalesOrderDebitOnRegistrationCompletedTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testIsSalesOrderDebitOnRegistrationCompletedReturnsTrueWhenTransactionLogContainsAtLeastOneSuccessfulRecord(): void
    {
        // Arrange
        $salesOrderEntity = $this->tester->createOrder(PaymentTransfer::HEIDELPAY_CREDIT_CARD_SECURE);
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();

        $heidelpayTransactionLogFailSeedData = [
            HeidelpayTransactionLogTransfer::ID_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
            HeidelpayTransactionLogTransfer::TRANSACTION_TYPE => HeidelpayConfig::TRANSACTION_TYPE_EXTERNAL_RESPONSE,
            HeidelpayTransactionLogTransfer::RESPONSE_CODE => HeidelpayTestConfig::HEIDELPAY_UNSUCCESS_RESPONSE,
        ];
        $heidelpayTransactionLogSuccessSeedData = [
            HeidelpayTransactionLogTransfer::RESPONSE_CODE => HeidelpayConfig::EXTERNAL_RESPONSE_TRANSACTION_STATUS_OK,
        ] + $heidelpayTransactionLogFailSeedData;
        $this->tester->haveHeidelpayTransactionLog($heidelpayTransactionLogFailSeedData);
        $this->tester->haveHeidelpayTransactionLog($heidelpayTransactionLogFailSeedData);
        $this->tester->haveHeidelpayTransactionLog($heidelpayTransactionLogSuccessSeedData);

        // Act
        $isSalesOrderDebitOnRegistrationCompleted = $heidelpayFacade->isSalesOrderDebitOnRegistrationCompleted($salesOrderEntity->getIdSalesOrder());

        // Assert
        $this->assertTrue($isSalesOrderDebitOnRegistrationCompleted);
    }

    /**
     * @return void
     */
    public function testIsSalesOrderDebitOnRegistrationCompletedReturnsFalseWhenTransactionLogContainsNoSuccessfulRecords(): void
    {
        // Arrange
        $salesOrderEntity = $this->tester->createOrder(PaymentTransfer::HEIDELPAY_CREDIT_CARD_SECURE);
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();

        $this->tester->haveHeidelpayTransactionLog([
            HeidelpayTransactionLogTransfer::ID_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
            HeidelpayTransactionLogTransfer::TRANSACTION_TYPE => HeidelpayConfig::TRANSACTION_TYPE_EXTERNAL_RESPONSE,
            HeidelpayTransactionLogTransfer::RESPONSE_CODE => HeidelpayTestConfig::HEIDELPAY_UNSUCCESS_RESPONSE,
        ]);

        // Act
        $isSalesOrderDebitOnRegistrationCompleted = $heidelpayFacade->isSalesOrderDebitOnRegistrationCompleted($salesOrderEntity->getIdSalesOrder());

        // Assert
        $this->assertFalse($isSalesOrderDebitOnRegistrationCompleted);
    }
}
