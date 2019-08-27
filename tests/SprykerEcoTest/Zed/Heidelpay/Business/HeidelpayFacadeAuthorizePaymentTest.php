<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\SuccessfulResponseHeidelpayBusinessFactory;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\UnsuccesfulResponseHeidelpayBusinessFactory;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeAuthorizePaymentTest
 */
class HeidelpayFacadeAuthorizePaymentTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testProcessSuccessfulExternalPaymentResponseForSofort(): void
    {
        $salesOrder = $this->createSuccessOrder();

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());

        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrder);

        $heidelpayFacade->authorizePayment($orderTransfer);

        $transaction = $this->createHeidelpayFactory()->createTransactionLogReader()
           ->findOrderAuthorizeTransactionLogByIdSalesOrder($salesOrder->getIdSalesOrder());

        $this->testSuccessfulHeidelpayPaymentResponse($transaction);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function createSuccessOrder(): SpySalesOrder
    {
        $orderBuilder = new PaymentBuilder($this->createHeidelpayFactory());
        $orderTransfer = $orderBuilder->createPayment(PaymentTransfer::HEIDELPAY_SOFORT);

        return $orderTransfer;
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createSuccessfulPaymentHeidelpayFactoryMock(): HeidelpayBusinessFactory
    {
        return new SuccessfulResponseHeidelpayBusinessFactory();
    }

    /**
     * @return void
     */
    public function testProcessUnsuccessfulExternalPaymentResponseForSofort(): void
    {
        $salesOrder = $this->createSuccessOrder();

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createUnsuccessfulPaymentHeidelpayFactoryMock());

        $orderTransfer = $this->getOrderTransfer($heidelpayFacade, $salesOrder);

        $heidelpayFacade->authorizePayment($orderTransfer);

        $transaction = $this->createHeidelpayFactory()->createTransactionLogReader()
            ->findOrderAuthorizeTransactionLogByIdSalesOrder($salesOrder->getIdSalesOrder());

        $this->testUnsuccessfulHeidelpayPaymentResponse($transaction);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createUnsuccessfulPaymentHeidelpayFactoryMock(): HeidelpayBusinessFactory
    {
        return new UnsuccesfulResponseHeidelpayBusinessFactory();
    }
}
