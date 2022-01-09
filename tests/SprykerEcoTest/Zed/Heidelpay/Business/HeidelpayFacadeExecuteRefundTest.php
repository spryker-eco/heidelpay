<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepository;
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeExecuteRefundTest
 */
class HeidelpayFacadeExecuteRefundTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testExecuteRefund(): void
    {
        //Arrange
        $salesOrder = $this->createOrder();
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrder);

        //Act
        $heidelpayFacade->executeRefund($orderTransfer);
        $transaction = $this->createRepository()
            ->findHeidelpayTransactionLogByIdSalesOrderAndTransactionTypeAndResponseCode(
                $orderTransfer->getIdSalesOrder(),
                HeidelpayConfig::TRANSACTION_TYPE_REFUND,
                HeidelpayTestConfig::HEIDELPAY_SUCCESS_RESPONSE,
            );

        //Assert
        $this->assertNotEmpty($transaction->getIdTransactionUnique());
        $this->assertNotEmpty($transaction->getResponsePayload());
        $this->assertNotEmpty($transaction->getRequestPayload());
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createOrder(): SpySalesOrder
    {
        $orderBuilder = new PaymentBuilder();

        return $orderBuilder->createPayment(PaymentTransfer::HEIDELPAY_DIRECT_DEBIT);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepository
     */
    protected function createRepository(): HeidelpayRepository
    {
        return new HeidelpayRepository();
    }
}
