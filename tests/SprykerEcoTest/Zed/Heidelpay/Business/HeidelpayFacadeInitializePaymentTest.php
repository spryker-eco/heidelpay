<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayEasyCreditPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Quote\QuoteMockTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer\CustomerTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer\CustomerAddressTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\SuccessfulResponseHeidelpayBusinessFactory;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\UnsuccesfulResponseHeidelpayBusinessFactory;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeGetPaymentBySalesOrderTest
 */
class HeidelpayFacadeInitializePaymentTest extends HeidelpayPaymentTest
{
    use QuoteMockTrait, CustomerAddressTrait, CustomerTrait;

    /**
     * @return void
     */
    public function testProcessSuccessfulInitializeRequest()
    {
        $salesOrder = $this->createSuccessOrder();
        $quoteTransfer = $this->createQuoteWithPaymentTransfer($salesOrder);

        $heidelpayFacade = (new HeidelpayFacade())->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());
        $heidelpayFacade->initializePayment($quoteTransfer);

        $transaction = $this->createHeidelpayFactory()->createTransactionLogReader()
            ->findQuoteInitializeTransactionLogByIdSalesOrder(1000);

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
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteWithPaymentTransfer(SpySalesOrder $orderEntity)
    {
        $quoteTransfer = $this->createQuote();
        $paymentTransfer = (new PaymentTransfer())
            ->setHeidelpayEasyCredit(
                (new HeidelpayEasyCreditPaymentTransfer())
            )
            ->setPaymentMethod(HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT);

        $quoteTransfer->setTotals(
            (new TotalsTransfer())
                ->setGrandTotal(10000)
        );

        $quoteTransfer->setPayment($paymentTransfer);
        $customer = $this->createOrGetCustomerByQuote($quoteTransfer);
        $address = $this->createCustomerAddressByCustomer($customer);
        $quoteTransfer->getShippingAddress()->setIdCustomerAddress(
            $address->getIdCustomerAddress()
        );

        return $quoteTransfer;
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createSuccessfulPaymentHeidelpayFactoryMock()
    {
        return new SuccessfulResponseHeidelpayBusinessFactory();
    }

    /**
     * @return void
     */
    public function testProcessUnsuccessfulInitializeRequest()
    {
        $salesOrder = $this->createSuccessOrder();
        $quoteTransfer = $this->createQuoteWithPaymentTransfer($salesOrder);

        $heidelpayFacade = (new HeidelpayFacade())->setFactory($this->createUnsuccessfulPaymentHeidelpayFactoryMock());
        $heidelpayFacade->initializePayment($quoteTransfer);

        $transaction = $this->createHeidelpayFactory()->createTransactionLogReader()
            ->findQuoteInitializeTransactionLogByIdSalesOrder(1000);

        $this->testUnsuccessfulHeidelpayPaymentResponse($transaction);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createUnsuccessfulPaymentHeidelpayFactoryMock()
    {
        return new UnsuccesfulResponseHeidelpayBusinessFactory();
    }
}