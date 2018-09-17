<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\SuccessfulResponseHeidelpayBusinessFactory;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeAuthorizeOnRegistrationPaymentTest
 */
class HeidelpayFacadeAuthorizeOnRegistrationPaymentTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testProcessAuthorizeOnRegistrationPayment()
    {
        $salesOrder = $this->createSuccessOrder();

        $heidelpayFacade = (new HeidelpayFacade())->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());
        $orderTransfer = $this->getPaymentTransfer($heidelpayFacade, $salesOrder);
        $this->heidelpayFacade->authorizeOnRegistrationPayment($orderTransfer);

        $transaction = $this->createHeidelpayFactory()->createTransactionLogReader()
            ->findOrderAuthorizeOnRegistrationTransactionLogByIdSalesOrder($salesOrder->getIdSalesOrder());

        $this->testSuccessfulHeidelpayPaymentResponse($transaction);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function createSuccessOrder()
    {
        $orderBuilder = new PaymentBuilder($this->createHeidelpayFactory());
        $orderTransfer = $orderBuilder->createPayment(PaymentTransfer::HEIDELPAY_EASY_CREDIT);
        return $orderTransfer;
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createSuccessfulPaymentHeidelpayFactoryMock()
    {
        return new SuccessfulResponseHeidelpayBusinessFactory();
    }
}
