<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Codeception\TestCase\Test;
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
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Shared\Heidelpay\QuoteUniqueIdGenerator;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order\OrderAddressTrait;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeFindCreditCardRegistrationByIdAndQuoteTest
 */
class HeidelpayFacadeFindCreditCardRegistrationByIdAndQuoteTest extends Test
{

    use OrderAddressTrait;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade
     */
    protected $heidelpayFacade;

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();

        $this->heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createHeidelpayFactory());

        $this->getModule('\\' . ConfigHelper::class)
            ->setConfig(HeidelpayConstants::CONFIG_ENCRYPTION_KEY, 'encryption_key');
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createHeidelpayFactory()
    {
        return new HeidelpayBusinessFactory();
    }

    /**
     * @return void
     */
    public function testSuccessfulFindCreditCardRegistrationByIdAndQuote()
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
        $this->assertEquals($response->getCreditCardInfo()->getAccountBrand(), HeidelpayTestConstants::CARD_BRAND);
    }

    /**
     * @return void
     */
    public function testUnsuccessfulFindCreditCardRegistrationByIdAndQuote()
    {
        $quote = $this->createQuote();
        $transfer = new HeidelpayRegistrationByIdAndQuoteRequestTransfer();
        $transfer->setQuote($quote);
        $creditCardRegistrationEntity = $this->createCardRegistrationTransfer($quote);
        $creditCardRegistrationEntity->setQuoteHash(HeidelpayTestConstants::CARD_QUOTE_HASH);
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
    public function createQuote()
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
    public function createCardRegistrationTransfer(QuoteTransfer $quoteTransfer)
    {
        $creditCardRegistrationEntity = new SpyPaymentHeidelpayCreditCardRegistration();
        $quoteHash = QuoteUniqueIdGenerator::getHashByCustomerEmailAndTotals($quoteTransfer);

        $creditCardRegistrationEntity->setAccountBrand(HeidelpayTestConstants::CARD_BRAND)
            ->setRegistrationNumber(HeidelpayTestConstants::REGISTRATION_NUMBER)
        ->setAccountHolder($this->getAccountHolder($quoteTransfer))
        ->setAccountExpiryMonth(1)
        ->setAccountExpiryYear(2030)
        ->setAccountNumber(HeidelpayTestConstants::CARD_ACCOUNT_NUMBER)
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
    protected function getAccountHolder(QuoteTransfer $qoute)
    {
        return vsprintf("%s %s",
            [
                $qoute->getCustomer()->getFirstName(),
                $qoute->getCustomer()->getLastName(),
            ]
        );
    }

    /**
     * @return void
     */
    protected function _after()
    {
        $query = SpyPaymentHeidelpayCreditCardRegistrationQuery::create()
            ->findByQuoteHash(HeidelpayTestConstants::CARD_QUOTE_HASH);
        $query->delete();
        $con = Propel::getConnection();
        $con->commit();
    }

}
