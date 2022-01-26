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
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface;
use SprykerEcoTest\Zed\Heidelpay\HeidelpayTestConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeExecuteDebitOnRegistrationTest
 */
class HeidelpayFacadeExecuteDebitOnRegistrationTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testSuccessfulExecuteDebitOnRegistration(): void
    {
        //Arrange
        $salesOrder = $this->createOrder();
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrder);

        //Act
        $heidelpayFacade->executeDebitOnRegistration($orderTransfer);
        $transaction = $this->createRepository()
            ->findHeidelpayTransactionLogByIdSalesOrderAndTransactionTypeAndResponseCode(
                $orderTransfer->getIdSalesOrder(),
                HeidelpayConfig::TRANSACTION_TYPE_DEBIT_ON_REGISTRATION,
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
        $paymentBuilder = new PaymentBuilder();

        return $paymentBuilder->createPayment(PaymentTransfer::HEIDELPAY_DIRECT_DEBIT);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface
     */
    protected function createRepository(): HeidelpayRepositoryInterface
    {
        return new HeidelpayRepository();
    }
}
