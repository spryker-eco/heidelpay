<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepository;
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\SuccessfulResponseHeidelpayBusinessFactory;

/**
 * @group Functional
 * @group Spryker
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
    public function testSuccessfulExecuteRefund(): void
    {
        //Arrange
        $salesOrder = $this->createSuccessOrder();
        $factory = $this->createSuccessfulPaymentHeidelpayFactoryMock();
        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($factory);
        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrder);

        //Act
        $heidelpayFacade->executeDebitOnRegistration($orderTransfer);
        $transaction = $this->createRepository()
            ->findHeidelpayTransactionLogByIdSalesOrderAndTransactionType(
                $orderTransfer->getIdSalesOrder(),
                HeidelpayConfig::TRANSACTION_TYPE_DEBIT_ON_REGISTRATION
            );

        //Assert
        $this->assertEquals(HeidelpayTestConfig::HEIDELPAY_SUCCESS_RESPONSE, $transaction->getResponseCode());
        $this->assertNotEmpty($transaction->getIdTransactionUnique());
        $this->assertNotEmpty($transaction->getResponsePayload());
        $this->assertNotEmpty($transaction->getRequestPayload());
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function createSuccessOrder(): SpySalesOrder
    {
        $orderBuilder = new PaymentBuilder($this->createHeidelpayFactory());

        return $orderBuilder->createPayment(PaymentTransfer::HEIDELPAY_DIRECT_DEBIT);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createSuccessfulPaymentHeidelpayFactoryMock(): HeidelpayBusinessFactory
    {
        return new SuccessfulResponseHeidelpayBusinessFactory();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepository
     */
    protected function createRepository(): HeidelpayRepository
    {
        return new HeidelpayRepository();
    }
}
