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
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order\OrderAddressTrait;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group Facade
 * @group FindCreditCardRegistrationByIdAndQuoteTest
 */
class FindCreditCardRegistrationByIdAndQuoteTest extends HeidelpayPaymentTest
{
    use OrderAddressTrait;

    /**
     * @return void
     */
    public function testSuccessfulFindCreditCardRegistrationByIdAndQuote(): void
    {
        //Arrange
        $quoteTransfer = $this->createQuote();
        $heidelpayRegistrationByIdAndQuoteRequestTransfer = new HeidelpayRegistrationByIdAndQuoteRequestTransfer();
        $heidelpayRegistrationByIdAndQuoteRequestTransfer->setQuote($quoteTransfer);
        $creditCardRegistrationEntity = $this->createCardRegistrationTransfer($quoteTransfer);
        $heidelpayRegistrationByIdAndQuoteRequestTransfer->setIdRegistration($creditCardRegistrationEntity->getIdCreditCardRegistration());

        //Act
        $response = $this->heidelpayFacade->findCreditCardRegistrationByIdAndQuote($heidelpayRegistrationByIdAndQuoteRequestTransfer);

        //Assert
        $this->assertInstanceOf(HeidelpayCreditCardRegistrationTransfer::class, $response);
        $this->assertNotNull($response->getIdCreditCardRegistration());
        $this->assertNotNull($response->getCreditCardInfo());
        $this->assertInstanceOf(HeidelpayCreditCardInfoTransfer::class, $response->getCreditCardInfo());
        $this->assertEquals($response->getCreditCardInfo()->getAccountHolder(), $this->getAccountHolder($quoteTransfer));
        $this->assertEquals($response->getCreditCardInfo()->getAccountBrand(), HeidelpayTestConfig::CARD_BRAND);
    }

    /**
     * @return void
     */
    public function testUnsuccessfulFindCreditCardRegistrationByIdAndQuote(): void
    {
        //Arrange
        $quoteTransfer = $this->createQuote();
        $heidelpayRegistrationByIdAndQuoteRequestTransfer = new HeidelpayRegistrationByIdAndQuoteRequestTransfer();
        $heidelpayRegistrationByIdAndQuoteRequestTransfer->setQuote($quoteTransfer);
        $creditCardRegistrationEntity = $this->createCardRegistrationTransfer($quoteTransfer);
        $creditCardRegistrationEntity->setQuoteHash(HeidelpayTestConfig::CARD_QUOTE_HASH);
        $creditCardRegistrationEntity->save();
        $heidelpayRegistrationByIdAndQuoteRequestTransfer->setIdRegistration($creditCardRegistrationEntity->getIdCreditCardRegistration());

        //Act
        $response = $this->heidelpayFacade->findCreditCardRegistrationByIdAndQuote($heidelpayRegistrationByIdAndQuoteRequestTransfer);

        //Assert
        $this->assertInstanceOf(HeidelpayCreditCardRegistrationTransfer::class, $response);
        $this->assertNull($response->getIdCreditCardRegistration());
        $this->assertNull($response->getCreditCardInfo());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuote(): QuoteTransfer
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
    protected function createCardRegistrationTransfer(QuoteTransfer $quoteTransfer): SpyPaymentHeidelpayCreditCardRegistration
    {
        $creditCardRegistrationEntity = (new SpyPaymentHeidelpayCreditCardRegistration())
            ->setAccountBrand(HeidelpayTestConfig::CARD_BRAND)
            ->setRegistrationNumber(HeidelpayTestConfig::REGISTRATION_NUMBER)
            ->setAccountHolder($this->getAccountHolder($quoteTransfer))
            ->setAccountExpiryMonth(1)
            ->setAccountExpiryYear(2030)
            ->setAccountNumber(HeidelpayTestConfig::CARD_ACCOUNT_NUMBER)
            ->setFkCustomerAddress($quoteTransfer->getBillingAddress()->getIdCustomerAddress())
            ->setQuoteHash(QuoteUniqueIdGenerator::getHashByCustomerEmailAndTotals($quoteTransfer));

        $creditCardRegistrationEntity->save();

        return $creditCardRegistrationEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getAccountHolder(QuoteTransfer $quoteTransfer): string
    {
        return vsprintf(
            '%s %s',
            [
                $quoteTransfer->getCustomer()->getFirstName(),
                $quoteTransfer->getCustomer()->getLastName(),
            ],
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
