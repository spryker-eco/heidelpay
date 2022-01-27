<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayEasyCreditPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer\CustomerAddressTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer\CustomerTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Quote\QuoteMockTrait;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group Facade
 * @group GetPaymentBySalesOrderTest
 */
class InitializePaymentTest extends HeidelpayPaymentTest
{
    use QuoteMockTrait;
    use CustomerAddressTrait;
    use CustomerTrait;

    /**
     * @return void
     */
    public function testProcessSuccessfulInitializeRequest(): void
    {
        //Arrange
        $quoteTransfer = $this->createQuoteWithPaymentTransfer();
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();

        //Act
        $responseTransfer = $heidelpayFacade->initializePayment($quoteTransfer);

        //Assert
        $this->testSuccessfulIntializeHeidelpayPaymentResponse($responseTransfer);
    }

    /**
     * @return void
     */
    public function testProcessUnsuccessfulInitializeRequest(): void
    {
        //Arrange
        $quoteTransfer = $this->createQuoteWithPaymentTransfer();
        $heidelpayFacade = $this->createFacadeWithUnsuccessfulFactory();

        //Act
        $responseTransfer = $heidelpayFacade->initializePayment($quoteTransfer);

        //Assert
        $this->testUnsuccessfulIntializeHeidelpayPaymentResponse($responseTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteWithPaymentTransfer(): QuoteTransfer
    {
        $quoteTransfer = $this->createQuote();
        $paymentTransfer = (new PaymentTransfer())
            ->setHeidelpayEasyCredit(
                (new HeidelpayEasyCreditPaymentTransfer()),
            )
            ->setPaymentMethod(HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT);

        $quoteTransfer->setTotals(
            (new TotalsTransfer())
                ->setGrandTotal(10000)
                ->setTaxTotal(
                    (new TaxTotalTransfer())
                        ->setAmount(1000),
                ),
        );

        $quoteTransfer->setPayment($paymentTransfer);
        $customer = $this->createOrGetCustomerByQuote($quoteTransfer);
        $address = $this->createCustomerAddressByCustomer($customer);
        $quoteTransfer
            ->getShippingAddress()
            ->setIdCustomerAddress($address->getIdCustomerAddress());

        return $quoteTransfer;
    }
}
