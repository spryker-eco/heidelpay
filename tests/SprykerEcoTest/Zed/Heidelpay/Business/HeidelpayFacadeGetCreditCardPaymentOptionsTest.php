<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\HeidelpayCreditCardInfoTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardPaymentTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentOptionTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration;
use Propel\Runtime\Propel;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\CreditCard\CreditCardBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer\CustomerAddressTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer\CustomerTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Quote\QuoteMockTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\SuccessfulResponseHeidelpayBusinessFactory;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\UnsuccesfulResponseHeidelpayBusinessFactory;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeGetCreditCardPaymentOptionsTest
 */
class HeidelpayFacadeGetCreditCardPaymentOptionsTest extends Test
{

    const CUSTOMER_ADDRESS_ID = 100000000;
    use QuoteMockTrait, CustomerAddressTrait, CustomerTrait;

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
    public function testSuccessfulGetCreditCardPaymentOptionsForNotRegisteredCard()
    {
        $quoteTransfer = $this->createQuoteForNotRegisteredCard();

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());

        $heidelpayCreditCardPaymentOptionsTransfer = $heidelpayFacade
            ->getCreditCardPaymentOptions($quoteTransfer);

        $this->assertNotNull($heidelpayCreditCardPaymentOptionsTransfer);
        $this->assertInstanceOf(HeidelpayCreditCardPaymentOptionsTransfer::class, $heidelpayCreditCardPaymentOptionsTransfer);
        $this->assertEquals(
            HeidelpayTestConstants::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL,
            $heidelpayCreditCardPaymentOptionsTransfer->getPaymentFrameUrl()
        );
        $this->assertNull($heidelpayCreditCardPaymentOptionsTransfer->getLastSuccessfulRegistration());
        $optionsList = $heidelpayCreditCardPaymentOptionsTransfer->getOptionsList();

        $this->assertEquals(1, $optionsList->count());
        $option = $optionsList[0];
        $this->assertInstanceOf(HeidelpayPaymentOptionTransfer::class, $option);
        $this->assertEquals(HeidelpayConfig::PAYMENT_OPTION_NEW_REGISTRATION, $option->getCode());
    }

    /**
     * @return void
     */
    public function testUnsuccessfulGetCreditCardPaymentOptionsForNotRegisteredCard()
    {
        $quoteTransfer = $this->createQuoteForNotRegisteredCard();

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createUnsuccessfulPaymentHeidelpayFactoryMock());

        $heidelpayCreditCardPaymentOptionsTransfer = $heidelpayFacade
            ->getCreditCardPaymentOptions($quoteTransfer);

        $this->checkUnsuccessfulGetOptionResponse($heidelpayCreditCardPaymentOptionsTransfer);
    }

    /**
     * @return void
     */
    public function testSuccessfulGetCreditCardPaymentOptionsForRegisteredCardWithSameAddress()
    {
        $quoteTransfer = $this->createQuoteForNotRegisteredCard();
        $cardEntity = $this->registerCard($quoteTransfer);

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());

        $heidelpayCreditCardPaymentOptionsTransfer = $heidelpayFacade
            ->getCreditCardPaymentOptions($quoteTransfer);

        $this->checkSuccessfulResponseForRegisteredCard($heidelpayCreditCardPaymentOptionsTransfer, $cardEntity, $quoteTransfer);
    }

    /**
     * @return void
     */
    public function testUnsuccessfulGetCreditCardPaymentOptionsForRegisteredCardWithSameAddressButWithoutSuccessfulTransaction()
    {
        $quoteTransfer = $this->createQuoteForNotRegisteredCard();

        $cardEntity = $this->registerCard($quoteTransfer);

        $this->addLastSuccessfulRegistration($quoteTransfer, $cardEntity);

        $customer = $this->createOrGetCustomerByQuote($quoteTransfer);
        $address = $this->createCustomerAddressByCustomer($customer);

        $quoteTransfer->getShippingAddress()->setIdCustomerAddress($address->getIdCustomerAddress());

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());

        $heidelpayCreditCardPaymentOptionsTransfer = $heidelpayFacade
            ->getCreditCardPaymentOptions($quoteTransfer);

        $this->assertNotNull($heidelpayCreditCardPaymentOptionsTransfer);
        $this->assertInstanceOf(HeidelpayCreditCardPaymentOptionsTransfer::class, $heidelpayCreditCardPaymentOptionsTransfer);
        $this->assertEquals(
            HeidelpayTestConstants::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL,
            $heidelpayCreditCardPaymentOptionsTransfer->getPaymentFrameUrl()
        );
        $this->assertNull($heidelpayCreditCardPaymentOptionsTransfer->getLastSuccessfulRegistration());
        $optionsList = $heidelpayCreditCardPaymentOptionsTransfer->getOptionsList();

        $this->assertEquals(1, $optionsList->count());
        $option = $optionsList[0];
        $this->assertInstanceOf(HeidelpayPaymentOptionTransfer::class, $option);
        $this->assertEquals(HeidelpayConfig::PAYMENT_OPTION_NEW_REGISTRATION, $option->getCode());
    }

    /**
     * @return void
     */
    public function testSuccessfulGetCreditCardPaymentOptionsForRegisteredCardWithSameAddressButWithoutSuccessfulLastTransaction()
    {
        $quoteTransfer = $this->createQuoteForNotRegisteredCard();

        $cardEntity = $this->registerCard($quoteTransfer);

        $this->addLastSuccessfulRegistration($quoteTransfer, $cardEntity);

        $customer = $this->createOrGetCustomerByQuote($quoteTransfer);
        $address = $this->createCustomerAddressByCustomer($customer);

        $quoteTransfer->getShippingAddress()->setIdCustomerAddress($address->getIdCustomerAddress());

        $cardEntity = $this->registerCard($quoteTransfer);

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());

        $heidelpayCreditCardPaymentOptionsTransfer = $heidelpayFacade
            ->getCreditCardPaymentOptions($quoteTransfer);

        $this->checkSuccessfulResponseForRegisteredCard($heidelpayCreditCardPaymentOptionsTransfer, $cardEntity, $quoteTransfer);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createSuccessfulPaymentHeidelpayFactoryMock()
    {
        return new SuccessfulResponseHeidelpayBusinessFactory();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createUnsuccessfulPaymentHeidelpayFactoryMock()
    {
        return new UnsuccesfulResponseHeidelpayBusinessFactory();
    }

    /**
     * @return void
     */
    protected function _after()
    {
        $con = Propel::getConnection();
        $con->commit();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteWithPaymentTransfer()
    {
        $quote = $this->createQuote();
        $paymentTransfer = (new PaymentTransfer())
            ->setHeidelpayCreditCardSecure(
                (new HeidelpayCreditCardPaymentTransfer())
                ->setPaymentOptions(
                    new HeidelpayCreditCardPaymentOptionsTransfer()
                )
            );
        $quote->setPayment($paymentTransfer);

        return $quote;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteForNotRegisteredCard()
    {
        $quoteTransfer = $this->createQuoteWithPaymentTransfer();
        $quoteTransfer->setTotals(
            (new TotalsTransfer())
                ->setGrandTotal(10000)
        );

        $customer = $this->createOrGetCustomerByQuote($quoteTransfer);
        $address = $this->createCustomerAddressByCustomer($customer);

        $quoteTransfer->getShippingAddress()->setIdCustomerAddress(
            $address->getIdCustomerAddress()
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration $cardEntity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addLastSuccessfulRegistration(QuoteTransfer $quoteTransfer, SpyPaymentHeidelpayCreditCardRegistration $cardEntity)
    {
        $lastSuccessfulRegistration = new HeidelpayCreditCardRegistrationTransfer();
        $lastSuccessfulRegistration->setIdCustomerAddress(
            $quoteTransfer->getShippingAddress()->getIdCustomerAddress()
        );
        $lastSuccessfulRegistration->setRegistrationNumber(
            $cardEntity->getRegistrationNumber()
        );
        $lastSuccessfulRegistration->setCreditCardInfo(new HeidelpayCreditCardInfoTransfer());

        $quoteTransfer->getPayment()
            ->getHeidelpayCreditCardSecure()
            ->getPaymentOptions()
            ->setLastSuccessfulRegistration(
                $lastSuccessfulRegistration
            );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration
     */
    protected function registerCard(QuoteTransfer $quoteTransfer)
    {
        $cardBuilder = new CreditCardBuilder();
        return $cardBuilder->createCreditCard($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer $heidelpayCreditCardPaymentOptionsTransfer
     *
     * @return void
     */
    protected function checkUnsuccessfulGetOptionResponse(HeidelpayCreditCardPaymentOptionsTransfer $heidelpayCreditCardPaymentOptionsTransfer)
    {
        $this->assertNull(
            $heidelpayCreditCardPaymentOptionsTransfer->getPaymentFrameUrl()
        );

        $this->assertNull($heidelpayCreditCardPaymentOptionsTransfer->getLastSuccessfulRegistration());
        $optionsList = $heidelpayCreditCardPaymentOptionsTransfer->getOptionsList();

        $this->assertEquals(0, $optionsList->count());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer $heidelpayCreditCardPaymentOptionsTransfer
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration $cardEntity
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function checkSuccessfulResponseForRegisteredCard(
        HeidelpayCreditCardPaymentOptionsTransfer $heidelpayCreditCardPaymentOptionsTransfer,
        SpyPaymentHeidelpayCreditCardRegistration $cardEntity,
        QuoteTransfer $quoteTransfer
    ) {
        $this->assertEquals(
            HeidelpayTestConstants::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL,
            $heidelpayCreditCardPaymentOptionsTransfer->getPaymentFrameUrl()
        );

        $this->assertNotNull($heidelpayCreditCardPaymentOptionsTransfer->getLastSuccessfulRegistration());

        $lastSuccessfulRegistration = $heidelpayCreditCardPaymentOptionsTransfer->getLastSuccessfulRegistration();
        $this->assertInstanceOf(
            HeidelpayCreditCardRegistrationTransfer::class,
            $lastSuccessfulRegistration
        );

        $this->assertEquals(
            HeidelpayTestConstants::REGISTRATION_NUMBER,
            $lastSuccessfulRegistration->getRegistrationNumber()
        );

        $this->assertEquals(
            $cardEntity->getIdCreditCardRegistration(),
            $lastSuccessfulRegistration->getIdCreditCardRegistration()
        );

        $this->assertEquals(
            $quoteTransfer->getShippingAddress()->getIdCustomerAddress(),
            $lastSuccessfulRegistration->getIdCustomerAddress()
        );

        $optionsList = $heidelpayCreditCardPaymentOptionsTransfer->getOptionsList();
        $this->assertEquals(2, $optionsList->count());

        $availableOptions = [];
        foreach ($optionsList as $optionTransfer) {
            $this->assertInstanceOf(HeidelpayPaymentOptionTransfer::class, $optionTransfer);
            $availableOptions[] = $optionTransfer->getCode();
        }

        $this->assertTrue(in_array(HeidelpayConfig::PAYMENT_OPTION_EXISTING_REGISTRATION, $availableOptions));
        $this->assertTrue(in_array(HeidelpayConfig::PAYMENT_OPTION_NEW_REGISTRATION, $availableOptions));
    }

}
