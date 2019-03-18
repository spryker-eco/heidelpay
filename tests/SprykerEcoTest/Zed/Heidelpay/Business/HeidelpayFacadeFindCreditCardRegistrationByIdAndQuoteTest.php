<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardInfoTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Orm\Zed\Heidelpay\Persistence\Base\SpyPaymentHeidelpayCreditCardRegistrationQuery;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration;
use Propel\Runtime\Propel;
use SprykerEco\Shared\Heidelpay\QuoteUniqueIdGenerator;
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order\OrderAddressTrait;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeFindCreditCardRegistrationByIdAndQuoteTest
 */
class HeidelpayFacadeFindCreditCardRegistrationByIdAndQuoteTest extends HeidelpayPaymentTest
{
    use OrderAddressTrait;

    /**
     * @return void
     */
    public function testSuccessfulFindCreditCardRegistrationByIdAndQuote(): void
    {
        $quote = $this->createQuote();
        $transfer = new HeidelpayRegistrationByIdAndQuoteRequestTransfer();
        $transfer->setQuote($quote);
        $creditCardRegistrationEntity = $this->createCardRegistrationTransfer($quote);
        $transfer->setIdRegistration($creditCardRegistrationEntity->getIdCreditCardRegistration());

        $response = $this->heidelpayFacade->findCreditCardRegistrationByIdAndQuote($transfer);

        $this->assertInstanceOf(HeidelpayCreditCardRegistrationTransfer::class, $response);
        $this->assertNotNull($response->getIdCreditCardRegistration());
        $this->assertNotNull($response->getCreditCardInfo());
        $this->assertInstanceOf(HeidelpayCreditCardInfoTransfer::class, $response->getCreditCardInfo());

        $this->assertEquals($response->getCreditCardInfo()->getAccountHolder(), $this->getAccountHolder($quote));
        $this->assertEquals($response->getCreditCardInfo()->getAccountBrand(), HeidelpayTestConfig::CARD_BRAND);
    }

    /**
     * @return void
     */
    public function testUnsuccessfulFindCreditCardRegistrationByIdAndQuote(): void
    {
        $quote = $this->createQuote();
        $transfer = new HeidelpayRegistrationByIdAndQuoteRequestTransfer();
        $transfer->setQuote($quote);
        $creditCardRegistrationEntity = $this->createCardRegistrationTransfer($quote);
        $creditCardRegistrationEntity->setQuoteHash(HeidelpayTestConfig::CARD_QUOTE_HASH);
        $creditCardRegistrationEntity->save();
        $transfer->setIdRegistration($creditCardRegistrationEntity->getIdCreditCardRegistration());

        $response = $this->heidelpayFacade->findCreditCardRegistrationByIdAndQuote($transfer);

        $this->assertInstanceOf(HeidelpayCreditCardRegistrationTransfer::class, $response);
        $this->assertNull($response->getIdCreditCardRegistration());
        $this->assertNull($response->getCreditCardInfo());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuote(): QuoteTransfer
    {
        $product = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $product->getSku()]);

        $quote = (new QuoteBuilder([CustomerTransfer::EMAIL => 'max@mustermann.de']))
            ->withItem([ItemTransfer::SKU => $product->getSku()])
            ->withCustomer()
            ->withTotals()
            ->withCurrency()
            ->withShippingAddress()
            ->withBillingAddress()
            ->build();

        return $quote;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration
     */
    public function createCardRegistrationTransfer(QuoteTransfer $quoteTransfer): SpyPaymentHeidelpayCreditCardRegistration
    {
        $creditCardRegistrationEntity = new SpyPaymentHeidelpayCreditCardRegistration();
        $quoteHash = QuoteUniqueIdGenerator::getHashByCustomerEmailAndTotals($quoteTransfer);

        $creditCardRegistrationEntity->setAccountBrand(HeidelpayTestConfig::CARD_BRAND)
            ->setRegistrationNumber(HeidelpayTestConfig::REGISTRATION_NUMBER)
        ->setAccountHolder($this->getAccountHolder($quoteTransfer))
        ->setAccountExpiryMonth(1)
        ->setAccountExpiryYear(2030)
        ->setAccountNumber(HeidelpayTestConfig::CARD_ACCOUNT_NUMBER)
        ->setFkCustomerAddress($quoteTransfer->getBillingAddress()->getIdCustomerAddress())

        ->setQuoteHash($quoteHash);
        $creditCardRegistrationEntity->save();

        return $creditCardRegistrationEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $qoute
     *
     * @return string
     */
    protected function getAccountHolder(QuoteTransfer $qoute): string
    {
        return vsprintf(
            "%s %s",
            [
                $qoute->getCustomer()->getFirstName(),
                $qoute->getCustomer()->getLastName(),
            ]
        );
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        $query = SpyPaymentHeidelpayCreditCardRegistrationQuery::create()
            ->findByQuoteHash(HeidelpayTestConfig::CARD_QUOTE_HASH);
        $query->delete();
        $con = Propel::getConnection();
        $con->commit();
    }
}
