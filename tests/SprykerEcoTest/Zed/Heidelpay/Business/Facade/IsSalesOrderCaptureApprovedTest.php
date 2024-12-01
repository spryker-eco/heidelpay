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
 * @group IsSalesOrderCaptureApprovedTest
 */
class IsSalesOrderCaptureApprovedTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testReturnsTrueWhenTransactionLogContainsAtLeastOneSuccessfulRecord(): void
    {
        // Arrange
        $salesOrderEntity = $this->tester->createOrder(PaymentTransfer::HEIDELPAY_CREDIT_CARD_SECURE);
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();

        $heidelpayTransactionLogFailSeedData = [
            HeidelpayTransactionLogTransfer::ID_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
            HeidelpayTransactionLogTransfer::TRANSACTION_TYPE => HeidelpayConfig::TRANSACTION_TYPE_CAPTURE,
            HeidelpayTransactionLogTransfer::RESPONSE_CODE => HeidelpayConfig::CAPTURE_TRANSACTION_STATUS_FAILED,
        ];
        $heidelpayTransactionLogSuccessSeedData = [
            HeidelpayTransactionLogTransfer::RESPONSE_CODE => HeidelpayConfig::CAPTURE_TRANSACTION_STATUS_OK,
        ] + $heidelpayTransactionLogFailSeedData;
        $this->tester->haveHeidelpayTransactionLog($heidelpayTransactionLogFailSeedData);
        $this->tester->haveHeidelpayTransactionLog($heidelpayTransactionLogSuccessSeedData);

        // Act
        $isSalesOrderCaptureApproved = $heidelpayFacade->isSalesOrderCaptureApproved($salesOrderEntity->getIdSalesOrder());

        // Assert
        $this->assertTrue($isSalesOrderCaptureApproved);
    }

    /**
     * @return void
     */
    public function testReturnsFalseWhenTransactionLogContainsNoSuccessfulRecords(): void
    {
        // Arrange
        $salesOrderEntity = $this->tester->createOrder(PaymentTransfer::HEIDELPAY_CREDIT_CARD_SECURE);
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();

        $this->tester->haveHeidelpayTransactionLog([
            HeidelpayTransactionLogTransfer::ID_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
            HeidelpayTransactionLogTransfer::TRANSACTION_TYPE => HeidelpayConfig::TRANSACTION_TYPE_CAPTURE,
            HeidelpayTransactionLogTransfer::RESPONSE_CODE => HeidelpayConfig::CAPTURE_TRANSACTION_STATUS_FAILED,
        ]);

        // Act
        $isSalesOrderCaptureApproved = $heidelpayFacade->isSalesOrderCaptureApproved($salesOrderEntity->getIdSalesOrder());

        // Assert
        $this->assertFalse($isSalesOrderCaptureApproved);
    }
}
