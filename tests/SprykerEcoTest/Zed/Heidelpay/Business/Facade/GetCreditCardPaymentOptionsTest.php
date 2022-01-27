<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayCreditCardInfoTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardPaymentTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentOptionTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\CreditCard\CreditCardBuilder;
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
 * @group GetCreditCardPaymentOptionsTest
 */
class GetCreditCardPaymentOptionsTest extends HeidelpayPaymentTest
{
    use QuoteMockTrait;
    use CustomerAddressTrait;
    use CustomerTrait;

    /**
     * @return void
     */
    public function testSuccessfulGetCreditCardPaymentOptionsForNotRegisteredCard(): void
    {
        //Arrange
        $quoteTransfer = $this->createQuoteForNotRegisteredCard();
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();

        //Act
        $heidelpayCreditCardPaymentOptionsTransfer = $heidelpayFacade->getCreditCardPaymentOptions($quoteTransfer);

        //Assert
        $this->assertNotNull($heidelpayCreditCardPaymentOptionsTransfer);
        $this->assertInstanceOf(HeidelpayCreditCardPaymentOptionsTransfer::class, $heidelpayCreditCardPaymentOptionsTransfer);
        $this->assertEquals(
            HeidelpayTestConfig::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL,
            $heidelpayCreditCardPaymentOptionsTransfer->getPaymentFrameUrl(),
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
    public function testUnsuccessfulGetCreditCardPaymentOptionsForNotRegisteredCard(): void
    {
        //Arrange
        $quoteTransfer = $this->createQuoteForNotRegisteredCard();
        $heidelpayFacade = $this->createFacadeWithUnsuccessfulFactory();

        //Act
        $heidelpayCreditCardPaymentOptionsTransfer = $heidelpayFacade->getCreditCardPaymentOptions($quoteTransfer);

        //Assert
        $this->checkUnsuccessfulGetOptionResponse($heidelpayCreditCardPaymentOptionsTransfer);
    }

    /**
     * @return void
     */
    public function testSuccessfulGetCreditCardPaymentOptionsForRegisteredCardWithSameAddress(): void
    {
        //Arrange
        $quoteTransfer = $this->createQuoteForNotRegisteredCard();
        $cardEntity = $this->registerCard($quoteTransfer);
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();

        //Act
        $heidelpayCreditCardPaymentOptionsTransfer = $heidelpayFacade->getCreditCardPaymentOptions($quoteTransfer);

        //Assert
        $this->checkSuccessfulResponseForRegisteredCard($heidelpayCreditCardPaymentOptionsTransfer, $cardEntity, $quoteTransfer);
    }

    /**
     * @return void
     */
    public function testUnsuccessfulGetCreditCardPaymentOptionsForRegisteredCardWithSameAddressButWithoutSuccessfulTransaction(): void
    {
        //Arrange
        $quoteTransfer = $this->createQuoteForNotRegisteredCard();
        $cardEntity = $this->registerCard($quoteTransfer);
        $quoteTransfer = $this->addLastSuccessfulRegistration($quoteTransfer, $cardEntity);
        $customer = $this->createOrGetCustomerByQuote($quoteTransfer);
        $address = $this->createCustomerAddressByCustomer($customer);
        $quoteTransfer
            ->getShippingAddress()
            ->setIdCustomerAddress($address->getIdCustomerAddress());

        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();

        //Act
        $heidelpayCreditCardPaymentOptionsTransfer = $heidelpayFacade->getCreditCardPaymentOptions($quoteTransfer);

        //Assert
        $this->assertNotNull($heidelpayCreditCardPaymentOptionsTransfer);
        $this->assertInstanceOf(HeidelpayCreditCardPaymentOptionsTransfer::class, $heidelpayCreditCardPaymentOptionsTransfer);
        $this->assertEquals(
            HeidelpayTestConfig::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL,
            $heidelpayCreditCardPaymentOptionsTransfer->getPaymentFrameUrl(),
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
    public function testSuccessfulGetCreditCardPaymentOptionsForRegisteredCardWithSameAddressButWithoutSuccessfulLastTransaction(): void
    {
        //Arrange
        $quoteTransfer = $this->createQuoteForNotRegisteredCard();
        $customer = $this->createOrGetCustomerByQuote($quoteTransfer);
        $address = $this->createCustomerAddressByCustomer($customer);
        $quoteTransfer
            ->getShippingAddress()
            ->setIdCustomerAddress($address->getIdCustomerAddress());

        $cardEntity = $this->registerCard($quoteTransfer);
        $quoteTransfer = $this->addLastSuccessfulRegistration($quoteTransfer, $cardEntity);
        $heidelpayFacade = $this->createFacadeWithSuccessfulFactory();

        //Act
        $heidelpayCreditCardPaymentOptionsTransfer = $heidelpayFacade->getCreditCardPaymentOptions($quoteTransfer);

        //Assert
        $this->checkSuccessfulResponseForRegisteredCard($heidelpayCreditCardPaymentOptionsTransfer, $cardEntity, $quoteTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteForNotRegisteredCard(): QuoteTransfer
    {
        $quoteTransfer = $this->createQuoteWithPaymentTransfer();
        $quoteTransfer->setTotals(
            (new TotalsTransfer())
                ->setGrandTotal(10000),
        );

        $customer = $this->createOrGetCustomerByQuote($quoteTransfer);
        $address = $this->createCustomerAddressByCustomer($customer);

        $quoteTransfer->getShippingAddress()->setIdCustomerAddress(
            $address->getIdCustomerAddress(),
        );

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteWithPaymentTransfer(): QuoteTransfer
    {
        $quoteTransfer = $this->createQuote();
        $paymentTransfer = (new PaymentTransfer())
            ->setHeidelpayCreditCardSecure(
                (new HeidelpayCreditCardPaymentTransfer())
                    ->setPaymentOptions(
                        new HeidelpayCreditCardPaymentOptionsTransfer(),
                    ),
            );
        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration $cardEntity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addLastSuccessfulRegistration(QuoteTransfer $quoteTransfer, SpyPaymentHeidelpayCreditCardRegistration $cardEntity): QuoteTransfer
    {
        $lastSuccessfulRegistration = new HeidelpayCreditCardRegistrationTransfer();
        $lastSuccessfulRegistration->setIdCustomerAddress(
            $quoteTransfer->getShippingAddress()->getIdCustomerAddress(),
        );
        $lastSuccessfulRegistration->setRegistrationNumber(
            $cardEntity->getRegistrationNumber(),
        );
        $lastSuccessfulRegistration->setCreditCardInfo(new HeidelpayCreditCardInfoTransfer());
        $lastSuccessfulRegistration->setIdCreditCardRegistration($cardEntity->getIdCreditCardRegistration());

        $quoteTransfer->getPayment()
            ->getHeidelpayCreditCardSecure()
            ->getPaymentOptions()
            ->setLastSuccessfulRegistration(
                $lastSuccessfulRegistration,
            );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration
     */
    protected function registerCard(QuoteTransfer $quoteTransfer): SpyPaymentHeidelpayCreditCardRegistration
    {
        $cardBuilder = new CreditCardBuilder();

        return $cardBuilder->createCreditCard($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer $heidelpayCreditCardPaymentOptionsTransfer
     *
     * @return void
     */
    protected function checkUnsuccessfulGetOptionResponse(HeidelpayCreditCardPaymentOptionsTransfer $heidelpayCreditCardPaymentOptionsTransfer): void
    {
        $this->assertNull($heidelpayCreditCardPaymentOptionsTransfer->getPaymentFrameUrl());
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
    ): void {
        $this->assertEquals(
            HeidelpayTestConfig::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL,
            $heidelpayCreditCardPaymentOptionsTransfer->getPaymentFrameUrl(),
        );

        $this->assertNotNull($heidelpayCreditCardPaymentOptionsTransfer->getLastSuccessfulRegistration());

        $lastSuccessfulRegistration = $heidelpayCreditCardPaymentOptionsTransfer->getLastSuccessfulRegistration();
        $this->assertInstanceOf(
            HeidelpayCreditCardRegistrationTransfer::class,
            $lastSuccessfulRegistration,
        );

        $this->assertEquals(
            HeidelpayTestConfig::REGISTRATION_NUMBER,
            $lastSuccessfulRegistration->getRegistrationNumber(),
        );

        $this->assertEquals(
            $cardEntity->getIdCreditCardRegistration(),
            $lastSuccessfulRegistration->getIdCreditCardRegistration(),
        );

        $this->assertEquals(
            $quoteTransfer->getShippingAddress()->getIdCustomerAddress(),
            $lastSuccessfulRegistration->getIdCustomerAddress(),
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
