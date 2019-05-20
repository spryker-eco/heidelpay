<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayEasyCreditPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer\CustomerAddressTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer\CustomerTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Quote\QuoteMockTrait;
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
    use QuoteMockTrait,
        CustomerAddressTrait,
        CustomerTrait;

    /**
     * @return void
     */
    public function testProcessSuccessfulInitializeRequest()
    {
        $salesOrder = $this->createSuccessOrder();
        $quoteTransfer = $this->createQuoteWithPaymentTransfer($salesOrder);

        $heidelpayFacade = (new HeidelpayFacade())->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());
        $responseTransfer = $heidelpayFacade->initializePayment($quoteTransfer);

        $this->testSuccessfulIntializeHeidelpayPaymentResponse($responseTransfer);
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
                ->setTaxTotal(
                    (new TaxTotalTransfer())
                        ->setAmount(1000)
                )
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
        $responseTransfer = $heidelpayFacade->initializePayment($quoteTransfer);

        $this->testUnsuccessfulIntializeHeidelpayPaymentResponse($responseTransfer);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createUnsuccessfulPaymentHeidelpayFactoryMock()
    {
        return new UnsuccesfulResponseHeidelpayBusinessFactory();
    }
}
