<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\Adapter\AdapterFactory;
use SprykerEco\Zed\Heidelpay\Business\Adapter\AdapterFactoryInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\DirectDebitPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Basket\BasketCreator;
use SprykerEco\Zed\Heidelpay\Business\Basket\BasketCreatorInterface;
use SprykerEco\Zed\Heidelpay\Business\Encrypter\AesEncrypter;
use SprykerEco\Zed\Heidelpay\Business\Encrypter\EncrypterInterface;
use SprykerEco\Zed\Heidelpay\Business\Hook\PostSaveHook;
use SprykerEco\Zed\Heidelpay\Business\Hook\PostSaveHookInterface;
use SprykerEco\Zed\Heidelpay\Business\Mapper\OrderToHeidelpayRequest;
use SprykerEco\Zed\Heidelpay\Business\Mapper\OrderToHeidelpayRequestInterface;
use SprykerEco\Zed\Heidelpay\Business\Mapper\QuoteToHeidelpayRequest;
use SprykerEco\Zed\Heidelpay\Business\Mapper\QuoteToHeidelpayRequestInterface;
use SprykerEco\Zed\Heidelpay\Business\Order\OrderReader;
use SprykerEco\Zed\Heidelpay\Business\Order\OrderReaderInterface;
use SprykerEco\Zed\Heidelpay\Business\Order\Saver;
use SprykerEco\Zed\Heidelpay\Business\Order\SaverInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\LastSuccessfulRegistration;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\NewRegistrationIframe;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOptionsCalculator;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOptionsCalculatorInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationReader;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationReaderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationSaver;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationSaverInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationWriter;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationWriterInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCardSecure;
use SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit;
use SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\DirectDebitPaymentOptionsCalculator;
use SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\DirectDebitPaymentOptionsCalculatorInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\PaymentOption\DirectDebitLastSuccessfulRegistration;
use SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\PaymentOption\DirectDebitNewRegistration;
use SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\PaymentOption\DirectDebitPaymentOptionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\Registration\DirectDebitRegistrationReader;
use SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\Registration\DirectDebitRegistrationReaderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\Registration\DirectDebitRegistrationWriter;
use SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\Registration\DirectDebitRegistrationWriterInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\EasyCredit;
use SprykerEco\Zed\Heidelpay\Business\Payment\Ideal;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentMethodFilter;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentMethodFilterInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReader;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReaderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriter;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaypalAuthorize;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaypalDebit;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilder;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilder;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\EasyCreditAdapterRequestFromOrderBuilder;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\EasyCreditAdapterRequestFromQuoteBuilder;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalEasyCreditPaymentResponseBuilder;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalEasyCreditPaymentResponseBuilderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilder;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Sofort;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeOnRegistrationTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeOnRegistrationTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\DebitTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\DebitTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\EasyCreditInitializeExternalResponseTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\FinalizeTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\FinalizeTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\AuthorizeOnRegistrationTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\AuthorizeOnRegistrationTransactionHandlerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\AuthorizeTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\AuthorizeTransactionHandlerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\CaptureTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\CaptureTransactionHandlerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\DebitTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\DebitTransactionHandlerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\ExternalEasyCreditResponseTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\ExternalEasyCreditResponseTransactionHandlerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\ExternalResponseTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\ExternalResponseTransactionHandlerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\FinalizeTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\FinalizeTransactionHandlerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\InitializeTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\InitializeTransactionHandlerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\ReservationTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\ReservationTransactionHandlerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\InitializeTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\InitializeTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLogger;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ReservationTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ReservationTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReader;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReaderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPreSavePaymentInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyFacadeInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyFacadeInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeInterface;
use SprykerEco\Zed\Heidelpay\Dependency\QueryContainer\HeidelpayToSalesQueryContainerInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayDependencyProvider;

/**
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\Heidelpay\HeidelpayConfig getConfig()
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayEntityManagerInterface getEntityManager()
 */
class HeidelpayBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\AuthorizeTransactionHandlerInterface
     */
    public function createAuthorizeTransactionHandler(): AuthorizeTransactionHandlerInterface
    {
        return new AuthorizeTransactionHandler(
            $this->createAuthorizeTransaction(),
            $this->getAuthorizePaymentMethodAdapterCollection(),
            $this->createAdapterRequestFromOrderBuilder()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\AuthorizeOnRegistrationTransactionHandlerInterface
     */
    public function createAuthorizeOnRegistrationTransactionHandler(): AuthorizeOnRegistrationTransactionHandlerInterface
    {
        return new AuthorizeOnRegistrationTransactionHandler(
            $this->createAuthorizeOnRegistrationTransaction(),
            $this->getAuthorizeOnRegistrationPaymentMethodAdapterCollection(),
            $this->createAdapterRequestFromOrderBuilder()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\InitializeTransactionHandlerInterface
     */
    public function createInitializeTransactionHandler(): InitializeTransactionHandlerInterface
    {
        return new InitializeTransactionHandler(
            $this->createInitializeTransaction(),
            $this->getInitializePaymentMethodAdapterCollection(),
            $this->createEasyCreditAdapterRequestFromQuoteBuilder(),
            $this->createBasketHanlder()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\DebitTransactionHandlerInterface
     */
    public function createDebitTransactionHandler(): DebitTransactionHandlerInterface
    {
        return new DebitTransactionHandler(
            $this->createDebitTransaction(),
            $this->getDebitPaymentMethodAdapterCollection(),
            $this->createAdapterRequestFromOrderBuilder()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\FinalizeTransactionHandlerInterface
     */
    public function createFinalizeTransactionHandler(): FinalizeTransactionHandlerInterface
    {
        return new FinalizeTransactionHandler(
            $this->createFinalizeTransaction(),
            $this->getFinalizePaymentMethodAdapterCollection(),
            $this->createEasyCreditAdapterRequestFromOrderBuilder(),
            $this->createPaymentWriter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\ReservationTransactionHandlerInterface
     */
    public function createReservationTransactionHandler(): ReservationTransactionHandlerInterface
    {
        return new ReservationTransactionHandler(
            $this->createReservationTransaction(),
            $this->getReservationPaymentMethodAdapterCollection(),
            $this->createAdapterRequestFromOrderBuilder(),
            $this->createPaymentWriter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\CaptureTransactionHandlerInterface
     */
    public function createCaptureTransactionHandler(): CaptureTransactionHandlerInterface
    {
        return new CaptureTransactionHandler(
            $this->createCaptureTransaction(),
            $this->getCapturePaymentMethodAdapterCollection(),
            $this->createAdapterRequestFromOrderBuilder()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\ExternalResponseTransactionHandlerInterface
     */
    public function createExternalResponseTransactionHandler(): ExternalResponseTransactionHandlerInterface
    {
        return new ExternalResponseTransactionHandler(
            $this->createExternalResponseTransaction(),
            $this->getExternalResponsePaymentMethodAdapterCollection(),
            $this->createExternalPaymentResponseBuilder(),
            $this->createPaymentWriter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\ExternalEasyCreditResponseTransactionHandlerInterface
     */
    public function createEasyCreditInitializeExternalResponseTransactionHandler(): ExternalEasyCreditResponseTransactionHandlerInterface
    {
        return new ExternalEasyCreditResponseTransactionHandler(
            $this->createEasyCreditInitializeExternalResponseTransaction(),
            $this->getExternalResponsePaymentMethodAdapterCollection(),
            $this->createExternalEasyCreditPaymentResponseBuilder(),
            $this->createPaymentWriter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\AdapterFactoryInterface
     */
    public function createAdapterFactory(): AdapterFactoryInterface
    {
        return new AdapterFactory();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface
     */
    public function createTransactionLogger(): TransactionLoggerInterface
    {
        return new TransactionLogger(
            $this->getUtilEncodingService(),
            $this->createAesEncrypter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Order\SaverInterface
     */
    public function createOrderSaver(): SaverInterface
    {
        return new Saver(
            $this->createBasketHanlder(),
            $this->getPaymentMethodWithPreSavePaymentCollection()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Hook\PostSaveHookInterface
     */
    public function createPostSaveHook(): PostSaveHookInterface
    {
        return new PostSaveHook($this->getPaymentMethodWithPostSaveOrderCollection());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReaderInterface
     */
    public function createPaymentReader(): PaymentReaderInterface
    {
        return new PaymentReader($this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface
     */
    public function createPaymentWriter(): PaymentWriterInterface
    {
        return new PaymentWriter($this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Order\OrderReaderInterface
     */
    public function createOrderReader(): OrderReaderInterface
    {
        return new OrderReader($this->getSalesQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReaderInterface
     */
    public function createTransactionLogReader(): TransactionLogReaderInterface
    {
        return new TransactionLogReader(
            $this->getQueryContainer(),
            $this->createAdapterFactory()->createTransactionParser(),
            $this->createOrderReader(),
            $this->createAesEncrypter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOptionsCalculatorInterface
     */
    public function createPaymentOptionsCalculator(): PaymentOptionsCalculatorInterface
    {
        return new PaymentOptionsCalculator($this->getCreditCardPaymentOptionsArray());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\DirectDebitPaymentOptionsCalculatorInterface
     */
    public function createDirectDebitPaymentOptionsCalculator(): DirectDebitPaymentOptionsCalculatorInterface
    {
        return new DirectDebitPaymentOptionsCalculator($this->getDirectDebitPaymentOptionsArray());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationReaderInterface
     */
    public function createCreditCardRegistrationReader(): RegistrationReaderInterface
    {
        return new RegistrationReader($this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationSaverInterface
     */
    public function createCreditCardRegistrationSaver(): RegistrationSaverInterface
    {
        return new RegistrationSaver($this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface[]
     */
    public function getCreditCardPaymentOptionsArray(): array
    {
        return [
            $this->createLastSuccessfulRegistrationOption(),
            $this->createNewRegistrationIframeOption(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface
     */
    public function createLastSuccessfulRegistrationOption(): PaymentOptionInterface
    {
        return new LastSuccessfulRegistration($this->createCreditCardRegistrationReader());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface
     */
    public function createNewRegistrationIframeOption(): PaymentOptionInterface
    {
        return new NewRegistrationIframe(
            $this->createAdapterRequestFromQuoteBuilder(),
            $this->getCreditCardPaymentMethodAdapter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\Registration\DirectDebitRegistrationWriterInterface
     */
    public function createDirectDebitRegistrationWriter(): DirectDebitRegistrationWriterInterface
    {
        return new DirectDebitRegistrationWriter(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\Registration\DirectDebitRegistrationReaderInterface
     */
    public function createDirectDebitRegistrationReader(): DirectDebitRegistrationReaderInterface
    {
        return new DirectDebitRegistrationReader($this->getRepository());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\PaymentOption\DirectDebitPaymentOptionInterface[]
     */
    public function getDirectDebitPaymentOptionsArray(): array
    {
        return [
            $this->createDirectDebitNewRegistrationOption(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\PaymentOption\DirectDebitPaymentOptionInterface
     */
    public function createDirectDebitLastSuccessfulRegistrationOption(): DirectDebitPaymentOptionInterface
    {
        return new DirectDebitLastSuccessfulRegistration($this->createCreditCardRegistrationReader());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\PaymentOption\DirectDebitPaymentOptionInterface
     */
    public function createDirectDebitNewRegistrationOption(): DirectDebitPaymentOptionInterface
    {
        return new DirectDebitNewRegistration(
            $this->createAdapterRequestFromQuoteBuilder(),
            $this->getDirectDebitPaymentMethod()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilderInterface
     */
    public function createExternalPaymentResponseBuilder(): ExternalPaymentResponseBuilderInterface
    {
        return new ExternalPaymentResponseBuilder($this->createPaymentReader());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalEasyCreditPaymentResponseBuilderInterface
     */
    public function createExternalEasyCreditPaymentResponseBuilder(): ExternalEasyCreditPaymentResponseBuilderInterface
    {
        return new ExternalEasyCreditPaymentResponseBuilder($this->createPaymentReader());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface[]
     */
    public function getAuthorizePaymentMethodAdapterCollection(): array
    {
        return $this
            ->createAdapterFactory()
            ->getAuthorizePaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeOnRegistrationInterface[]
     */
    public function getAuthorizeOnRegistrationPaymentMethodAdapterCollection(): array
    {
        return $this
            ->createAdapterFactory()
            ->getAuthorizeOnRegistrationPaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface[]
     */
    public function getCapturePaymentMethodAdapterCollection(): array
    {
        return $this
            ->createAdapterFactory()
            ->getCapturePaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithInitializeInterface[]
     */
    public function getInitializePaymentMethodAdapterCollection(): array
    {
        return $this
            ->createAdapterFactory()
            ->getInitializePaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface[]
     */
    public function getDebitPaymentMethodAdapterCollection(): array
    {
        return $this
            ->createAdapterFactory()
            ->getDebitPaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithFinalizeInterface[]
     */
    public function getFinalizePaymentMethodAdapterCollection(): array
    {
        return $this
            ->createAdapterFactory()
            ->getFinalizePaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithReservationInterface[]
     */
    public function getReservationPaymentMethodAdapterCollection(): array
    {
        return $this
            ->createAdapterFactory()
            ->getReservationPaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface[]
     */
    public function getExternalResponsePaymentMethodAdapterCollection(): array
    {
        return $this
            ->createAdapterFactory()
            ->getExternalResponsePaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface
     */
    public function createAdapterRequestFromOrderBuilder(): AdapterRequestFromOrderBuilderInterface
    {
        return new AdapterRequestFromOrderBuilder(
            $this->createOrderToHeidelpayRequestMapper(),
            $this->getCurrencyFacade(),
            $this->getConfig(),
            $this->createPaymentReader()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface
     */
    public function createEasyCreditAdapterRequestFromOrderBuilder(): AdapterRequestFromOrderBuilderInterface
    {
        return new EasyCreditAdapterRequestFromOrderBuilder(
            $this->createOrderToHeidelpayRequestMapper(),
            $this->getCurrencyFacade(),
            $this->getConfig(),
            $this->createPaymentReader()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderInterface
     */
    public function createAdapterRequestFromQuoteBuilder(): AdapterRequestFromQuoteBuilderInterface
    {
        return new AdapterRequestFromQuoteBuilder(
            $this->createQuoteToHeidelpayRequestMapper(),
            $this->getCurrencyFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderInterface
     */
    public function createEasyCreditAdapterRequestFromQuoteBuilder(): AdapterRequestFromQuoteBuilderInterface
    {
        return new EasyCreditAdapterRequestFromQuoteBuilder(
            $this->createQuoteToHeidelpayRequestMapper(),
            $this->getCurrencyFacade(),
            $this->getConfig(),
            $this->getSalesFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Mapper\OrderToHeidelpayRequestInterface
     */
    public function createOrderToHeidelpayRequestMapper(): OrderToHeidelpayRequestInterface
    {
        return new OrderToHeidelpayRequest($this->getMoneyFacade());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Mapper\QuoteToHeidelpayRequestInterface
     */
    public function createQuoteToHeidelpayRequestMapper(): QuoteToHeidelpayRequestInterface
    {
        return new QuoteToHeidelpayRequest($this->getMoneyFacade());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeTransactionInterface
     */
    public function createAuthorizeTransaction(): AuthorizeTransactionInterface
    {
        return new AuthorizeTransaction($this->createTransactionLogger());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeOnRegistrationTransactionInterface
     */
    public function createAuthorizeOnRegistrationTransaction(): AuthorizeOnRegistrationTransactionInterface
    {
        return new AuthorizeOnRegistrationTransaction($this->createTransactionLogger());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\InitializeTransactionInterface
     */
    public function createInitializeTransaction(): InitializeTransactionInterface
    {
        return new InitializeTransaction();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\DebitTransactionInterface
     */
    public function createDebitTransaction(): DebitTransactionInterface
    {
        return new DebitTransaction($this->createTransactionLogger());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\FinalizeTransactionInterface
     */
    public function createFinalizeTransaction(): FinalizeTransactionInterface
    {
        return new FinalizeTransaction($this->createTransactionLogger());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ReservationTransactionInterface
     */
    public function createReservationTransaction(): ReservationTransactionInterface
    {
        return new ReservationTransaction($this->createTransactionLogger());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransactionInterface
     */
    public function createCaptureTransaction(): CaptureTransactionInterface
    {
        return new CaptureTransaction($this->createTransactionLogger());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface[]
     */
    public function getPaymentMethodWithPostSaveOrderCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_SOFORT => $this->createPaymentMethodSofort(),
            HeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE => $this->createPaymentMethodPaypalAuthorize(),
            HeidelpayConfig::PAYMENT_METHOD_PAYPAL_DEBIT => $this->createPaymentMethodPaypalDebit(),
            HeidelpayConfig::PAYMENT_METHOD_IDEAL => $this->createPaymentMethodIdeal(),
            HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createPaymentMethodCreditCardSecure(),
        ];
    }

    /**
     * @return array
     */
    public function getPaymentMethodWithPreSavePaymentCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createPaymentMethodCreditCardSecure(),
            HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => $this->createPaymentMethodEasyCredit(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    public function createPaymentMethodSofort(): PaymentWithPostSaveOrderInterface
    {
        return new Sofort(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    public function createPaymentMethodPaypalAuthorize(): PaymentWithPostSaveOrderInterface
    {
        return new PaypalAuthorize(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    public function createPaymentMethodPaypalDebit(): PaymentWithPostSaveOrderInterface
    {
        return new PaypalDebit(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPreSavePaymentInterface
     */
    public function createPaymentMethodEasyCredit(): PaymentWithPreSavePaymentInterface
    {
        return new EasyCredit(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    public function createPaymentMethodIdeal(): PaymentWithPostSaveOrderInterface
    {
        return new Ideal(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    public function createPaymentMethodCreditCardSecure(): PaymentWithPostSaveOrderInterface
    {
        return new CreditCardSecure(
            $this->createTransactionLogReader(),
            $this->getConfig(),
            $this->createCreditCardRegistrationWriter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    public function createDirectDebit(): PaymentWithPostSaveOrderInterface
    {
        return new DirectDebit(
            $this->createTransactionLogReader(),
            $this->getConfig(),
            $this->createCreditCardRegistrationWriter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationWriterInterface
     */
    public function createCreditCardRegistrationWriter(): RegistrationWriterInterface
    {
        return new RegistrationWriter($this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransactionInterface
     */
    public function createExternalResponseTransaction(): ExternalResponseTransactionInterface
    {
        return new ExternalResponseTransaction($this->createTransactionLogger());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransactionInterface
     */
    public function createEasyCreditInitializeExternalResponseTransaction(): ExternalResponseTransactionInterface
    {
        return new EasyCreditInitializeExternalResponseTransaction();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Encrypter\EncrypterInterface
     */
    public function createAesEncrypter(): EncrypterInterface
    {
        return new AesEncrypter($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeInterface
     */
    public function getSalesFacade(): HeidelpayToSalesFacadeInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): HeidelpayToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface
     */
    public function getCreditCardPaymentMethodAdapter(): CreditCardPaymentInterface
    {
        return $this
            ->createAdapterFactory()
            ->createCreditCardPaymentMethodAdapter();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\DirectDebitPaymentInterface
     */
    public function getDirectDebitPaymentMethod(): DirectDebitPaymentInterface
    {
        return $this
            ->createAdapterFactory()
            ->createDirectDebitPaymentMethod();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\QueryContainer\HeidelpayToSalesQueryContainerInterface
     */
    public function getSalesQueryContainer(): HeidelpayToSalesQueryContainerInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyFacadeInterface
     */
    public function getMoneyFacade(): HeidelpayToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): HeidelpayToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Basket\BasketCreatorInterface
     */
    public function createBasketHanlder(): BasketCreatorInterface
    {
        return new BasketCreator(
            $this->createAdapterFactory()->createBasketAdapter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentMethodFilterInterface
     */
    public function createPaymentMethodFilter(): PaymentMethodFilterInterface
    {
        return new PaymentMethodFilter(
            $this->getConfig(),
            $this->getMoneyFacade()
        );
    }
}
