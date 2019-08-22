<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayDirectDebitAccountTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitPaymentTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentOptionTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistration;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer\CustomerAddressTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer\CustomerTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\DirectDebit\DirectDebitRegistrationBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Quote\QuoteMockTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\SuccessfulResponseHeidelpayBusinessFactory;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\UnsuccesfulResponseHeidelpayBusinessFactory;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeGetDirectDebitPaymentOptionsTest
 */
class HeidelpayFacadeGetDirectDebitPaymentOptionsTest extends HeidelpayPaymentTest
{
    use QuoteMockTrait,
        CustomerAddressTrait,
        CustomerTrait;

    /**
     * @return void
     */
    public function testSuccessfulGetDirectDebitPaymentOptionsForNotRegisteredAccount(): void
    {
        //Arrange
        $quoteTransfer = $this->createQuoteForNotRegisteredAccount();

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());

        //Act
        $heidelpayDirectDebitPaymentOptionsTransfer = $heidelpayFacade->getDirectDebitPaymentOptions($quoteTransfer);

        //Assert
        $this->assertNotNull($heidelpayDirectDebitPaymentOptionsTransfer);
        $this->assertInstanceOf(HeidelpayDirectDebitPaymentOptionsTransfer::class, $heidelpayDirectDebitPaymentOptionsTransfer);
        $this->assertEquals(
            HeidelpayTestConfig::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL,
            $heidelpayDirectDebitPaymentOptionsTransfer->getPaymentFormActionUrl()
        );
        $this->assertNull($heidelpayDirectDebitPaymentOptionsTransfer->getLastSuccessfulRegistration());
        $optionsList = $heidelpayDirectDebitPaymentOptionsTransfer->getOptionsList();
        $this->assertEquals(1, $optionsList->count());
        $option = $optionsList[0];
        $this->assertInstanceOf(HeidelpayPaymentOptionTransfer::class, $option);
        $this->assertEquals(HeidelpayConfig::DIRECT_DEBIT_PAYMENT_OPTION_NEW_REGISTRATION, $option->getCode());
    }

    /**
     * @return void
     */
    public function testUnsuccessfulGetDirectDebitPaymentOptionsForNotRegisteredAccount(): void
    {
        //Arrange
        $quoteTransfer = $this->createQuoteForNotRegisteredAccount();

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createUnsuccessfulPaymentHeidelpayFactoryMock());

        //Act
        $heidelpayDirectDebitPaymentOptionsTransfer = $heidelpayFacade->getDirectDebitPaymentOptions($quoteTransfer);

        //Assert
        $this->assertNull($heidelpayDirectDebitPaymentOptionsTransfer->getPaymentFormActionUrl());
        $this->assertNull($heidelpayDirectDebitPaymentOptionsTransfer->getLastSuccessfulRegistration());
        $optionsList = $heidelpayDirectDebitPaymentOptionsTransfer->getOptionsList();
        $this->assertEquals(0, $optionsList->count());
    }

    /**
     * @return void
     */
    public function testSuccessfulGetDirectDebitPaymentOptionsForRegisteredAccountWithSameAddress(): void
    {
        //Arrange
        $quoteTransfer = $this->createQuoteForNotRegisteredAccount();
        $directDebitAccountEntity = $this->registerDirectDebitAccount($quoteTransfer);

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());

        //Act
        $heidelpayDirectDebitPaymentOptionsTransfer = $heidelpayFacade->getDirectDebitPaymentOptions($quoteTransfer);

        //Assert
        $this->assertEquals(
            HeidelpayTestConfig::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL,
            $heidelpayDirectDebitPaymentOptionsTransfer->getPaymentFormActionUrl()
        );

        $lastSuccessfulRegistration = $heidelpayDirectDebitPaymentOptionsTransfer->getLastSuccessfulRegistration();
        $this->assertInstanceOf(HeidelpayDirectDebitRegistrationTransfer::class, $lastSuccessfulRegistration);

        $this->assertEquals(
            HeidelpayTestConfig::REGISTRATION_NUMBER,
            $lastSuccessfulRegistration->getRegistrationUniqueId()
        );

        $this->assertEquals(
            $directDebitAccountEntity->getIdDirectDebitRegistration(),
            $lastSuccessfulRegistration->getIdDirectDebitRegistration()
        );

        $this->assertEquals(
            $quoteTransfer->getShippingAddress()->getIdCustomerAddress(),
            $lastSuccessfulRegistration->getIdCustomerAddress()
        );

        $optionsList = $heidelpayDirectDebitPaymentOptionsTransfer->getOptionsList();
        $this->assertEquals(2, $optionsList->count());

        $availableOptions = [];
        foreach ($optionsList as $optionTransfer) {
            $this->assertInstanceOf(HeidelpayPaymentOptionTransfer::class, $optionTransfer);
            $availableOptions[] = $optionTransfer->getCode();
        }

        $this->assertTrue(in_array(HeidelpayConfig::DIRECT_DEBIT_PAYMENT_OPTION_EXISTING_REGISTRATION, $availableOptions));
        $this->assertTrue(in_array(HeidelpayConfig::DIRECT_DEBIT_PAYMENT_OPTION_NEW_REGISTRATION, $availableOptions));
    }

    /**
     * @return void
     */
    public function testUnsuccessfulGetDirectDebitPaymentOptionsForRegisteredAccountWithSameAddressButWithoutSuccessfulTransaction(): void
    {
        //Arrange
        $quoteTransfer = $this->createQuoteForNotRegisteredAccount();
        $directDebitAccountEntity = $this->registerDirectDebitAccount($quoteTransfer);
        $quoteTransfer = $this->addLastSuccessfulRegistration($quoteTransfer, $directDebitAccountEntity);
        $customer = $this->createOrGetCustomerByQuote($quoteTransfer);
        $address = $this->createCustomerAddressByCustomer($customer);
        $quoteTransfer->getShippingAddress()->setIdCustomerAddress($address->getIdCustomerAddress());

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());

        //Act
        $heidelpayDirectDebitPaymentOptionsTransfer = $heidelpayFacade->getDirectDebitPaymentOptions($quoteTransfer);

        //Assert
        $this->assertInstanceOf(
            HeidelpayDirectDebitPaymentOptionsTransfer::class,
            $heidelpayDirectDebitPaymentOptionsTransfer
        );
        $this->assertEquals(
            HeidelpayTestConfig::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL,
            $heidelpayDirectDebitPaymentOptionsTransfer->getPaymentFormActionUrl()
        );
        $this->assertNull($heidelpayDirectDebitPaymentOptionsTransfer->getLastSuccessfulRegistration());
        $optionsList = $heidelpayDirectDebitPaymentOptionsTransfer->getOptionsList();

        $this->assertEquals(1, $optionsList->count());
        $option = $optionsList[0];
        $this->assertInstanceOf(HeidelpayPaymentOptionTransfer::class, $option);
        $this->assertEquals(HeidelpayConfig::DIRECT_DEBIT_PAYMENT_OPTION_NEW_REGISTRATION, $option->getCode());
    }

    /**
     * @return void
     */
    public function testSuccessfulGetDirectDebitPaymentOptionsForRegisteredAccountWithSameAddressButWithoutLastSuccessfulRegistration(): void
    {
        //Arrange
        $quoteTransfer = $this->createQuoteForNotRegisteredAccount();
        $directDebitAccountEntity = $this->registerDirectDebitAccount($quoteTransfer);
        $quoteTransfer = $this->addLastSuccessfulRegistration($quoteTransfer, $directDebitAccountEntity);

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createSuccessfulPaymentHeidelpayFactoryMock());

        //Act
        $heidelpayDirectDebitPaymentOptionsTransfer = $heidelpayFacade->getDirectDebitPaymentOptions($quoteTransfer);

        //Assert
        $this->assertEquals(
            HeidelpayTestConfig::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL,
            $heidelpayDirectDebitPaymentOptionsTransfer->getPaymentFormActionUrl()
        );

        $lastSuccessfulRegistration = $heidelpayDirectDebitPaymentOptionsTransfer->getLastSuccessfulRegistration();
        $this->assertInstanceOf(HeidelpayDirectDebitRegistrationTransfer::class, $lastSuccessfulRegistration);

        $this->assertEquals(
            HeidelpayTestConfig::REGISTRATION_NUMBER,
            $lastSuccessfulRegistration->getRegistrationUniqueId()
        );

        $this->assertEquals(
            $directDebitAccountEntity->getIdDirectDebitRegistration(),
            $lastSuccessfulRegistration->getIdDirectDebitRegistration()
        );

        $this->assertEquals(
            $quoteTransfer->getShippingAddress()->getIdCustomerAddress(),
            $lastSuccessfulRegistration->getIdCustomerAddress()
        );

        $optionsList = $heidelpayDirectDebitPaymentOptionsTransfer->getOptionsList();
        $this->assertEquals(2, $optionsList->count());

        $availableOptions = [];
        foreach ($optionsList as $optionTransfer) {
            $this->assertInstanceOf(HeidelpayPaymentOptionTransfer::class, $optionTransfer);
            $availableOptions[] = $optionTransfer->getCode();
        }

        $this->assertTrue(in_array(HeidelpayConfig::DIRECT_DEBIT_PAYMENT_OPTION_EXISTING_REGISTRATION, $availableOptions));
        $this->assertTrue(in_array(HeidelpayConfig::DIRECT_DEBIT_PAYMENT_OPTION_NEW_REGISTRATION, $availableOptions));
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createSuccessfulPaymentHeidelpayFactoryMock(): HeidelpayBusinessFactory
    {
        return new SuccessfulResponseHeidelpayBusinessFactory();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createUnsuccessfulPaymentHeidelpayFactoryMock(): HeidelpayBusinessFactory
    {
        return new UnsuccesfulResponseHeidelpayBusinessFactory();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteWithPaymentTransfer(): QuoteTransfer
    {
        $quoteTransfer = $this->createQuote();
        $paymentTransfer = (new PaymentTransfer())
            ->setHeidelpayDirectDebit(
                (new HeidelpayDirectDebitPaymentTransfer())
                ->setPaymentOptions(
                    new HeidelpayDirectDebitPaymentOptionsTransfer()
                )
            );
        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteForNotRegisteredAccount(): QuoteTransfer
    {
        $quoteTransfer = $this->createQuoteWithPaymentTransfer();
        $quoteTransfer->setTotals(
            (new TotalsTransfer())
                ->setGrandTotal(10000)
        );

        $customer = $this->createOrGetCustomerByQuote($quoteTransfer);
        $address = $this->createCustomerAddressByCustomer($customer);

        $quoteTransfer->getShippingAddress()
            ->setIdCustomerAddress($address->getIdCustomerAddress());

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistration $directDebitRegistrationEntity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addLastSuccessfulRegistration(
        QuoteTransfer $quoteTransfer,
        SpyPaymentHeidelpayDirectDebitRegistration $directDebitRegistrationEntity
    ): QuoteTransfer {
        $lastSuccessfulRegistration = (new HeidelpayDirectDebitRegistrationTransfer())
            ->setIdCustomerAddress(
                $quoteTransfer->getShippingAddress()->getIdCustomerAddress()
            )
            ->setRegistrationUniqueId(
                $directDebitRegistrationEntity->getRegistrationUniqueId()
            )
            ->setAccountInfo(new HeidelpayDirectDebitAccountTransfer())
            ->setIdDirectDebitRegistration($directDebitRegistrationEntity->getIdDirectDebitRegistration());

        $quoteTransfer->getPayment()
            ->getHeidelpayDirectDebit()
            ->getPaymentOptions()
            ->setLastSuccessfulRegistration($lastSuccessfulRegistration);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistration
     */
    protected function registerDirectDebitAccount(QuoteTransfer $quoteTransfer): SpyPaymentHeidelpayDirectDebitRegistration
    {
        $directDebitRegistrationBuilder = new DirectDebitRegistrationBuilder();

        return $directDebitRegistrationBuilder->createDirectDebitAccount($quoteTransfer);
    }
}
