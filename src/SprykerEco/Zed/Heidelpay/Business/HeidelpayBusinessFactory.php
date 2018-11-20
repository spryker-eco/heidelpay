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
use SprykerEco\Zed\Heidelpay\Business\Payment\Ideal;
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
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilder;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Sofort;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\DebitTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\DebitTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\AuthorizeTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\AuthorizeTransactionHandlerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\CaptureTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\CaptureTransactionHandlerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\DebitTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\DebitTransactionHandlerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\ExternalResponseTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\ExternalResponseTransactionHandlerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLogger;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReader;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReaderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesInterface;
use SprykerEco\Zed\Heidelpay\Dependency\QueryContainer\HeidelpayToSalesQueryContainerInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayDependencyProvider;

/**
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\Heidelpay\HeidelpayConfig getConfig()
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
        return new OrderReader(
            $this->getSalesQueryContainer()
        );
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
        return new PaymentOptionsCalculator(
            $this->getCreditCardPaymentOptionsArray()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationReaderInterface
     */
    public function createCreditCardRegistrationReader(): RegistrationReaderInterface
    {
        return new RegistrationReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationSaverInterface
     */
    public function createCreditCardRegistrationSaver(): RegistrationSaverInterface
    {
        return new RegistrationSaver(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface[]
     */
    protected function getCreditCardPaymentOptionsArray(): array
    {
        return [
            $this->createLastSuccessfulRegistrationOption(),
            $this->createNewRegistrationIframeOption(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface
     */
    protected function createLastSuccessfulRegistrationOption(): PaymentOptionInterface
    {
        return new LastSuccessfulRegistration(
            $this->createCreditCardRegistrationReader()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface
     */
    protected function createNewRegistrationIframeOption(): PaymentOptionInterface
    {
        return new NewRegistrationIframe(
            $this->createAdapterRequestFromQuoteBuilder(),
            $this->getCreditCardPaymentMethodAdapter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilderInterface
     */
    protected function createExternalPaymentResponseBuilder(): ExternalPaymentResponseBuilderInterface
    {
        return new ExternalPaymentResponseBuilder(
            $this->createPaymentReader()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface[]
     */
    protected function getAuthorizePaymentMethodAdapterCollection(): array
    {
        return $this
            ->createAdapterFactory()
            ->getAuthorizePaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface[]
     */
    protected function getCapturePaymentMethodAdapterCollection(): array
    {
        return $this
            ->createAdapterFactory()
            ->getCapturePaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface[]
     */
    protected function getDebitPaymentMethodAdapterCollection(): array
    {
        return $this
            ->createAdapterFactory()
            ->getDebitPaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface[]
     */
    protected function getExternalResponsePaymentMethodAdapterCollection(): array
    {
        return $this
            ->createAdapterFactory()
            ->getExternalResponsePaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface
     */
    protected function createAdapterRequestFromOrderBuilder(): AdapterRequestFromOrderBuilderInterface
    {
        return new AdapterRequestFromOrderBuilder(
            $this->createOrderToHeidelpayRequestMapper(),
            $this->getCurrencyFacade(),
            $this->getConfig(),
            $this->createPaymentReader()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderInterface
     */
    protected function createAdapterRequestFromQuoteBuilder(): AdapterRequestFromQuoteBuilderInterface
    {
        return new AdapterRequestFromQuoteBuilder(
            $this->createQuoteToHeidelpayRequestMapper(),
            $this->getCurrencyFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Mapper\OrderToHeidelpayRequestInterface
     */
    protected function createOrderToHeidelpayRequestMapper(): OrderToHeidelpayRequestInterface
    {
        return new OrderToHeidelpayRequest(
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Mapper\QuoteToHeidelpayRequestInterface
     */
    protected function createQuoteToHeidelpayRequestMapper(): QuoteToHeidelpayRequestInterface
    {
        return new QuoteToHeidelpayRequest(
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeTransactionInterface
     */
    protected function createAuthorizeTransaction(): AuthorizeTransactionInterface
    {
        return new AuthorizeTransaction(
            $this->createTransactionLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\DebitTransactionInterface
     */
    protected function createDebitTransaction(): DebitTransactionInterface
    {
        return new DebitTransaction(
            $this->createTransactionLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransactionInterface
     */
    protected function createCaptureTransaction(): CaptureTransactionInterface
    {
        return new CaptureTransaction(
            $this->createTransactionLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface[]
     */
    protected function getPaymentMethodWithPostSaveOrderCollection(): array
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
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPreSavePaymentInterface[]
     */
    protected function getPaymentMethodWithPreSavePaymentCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createPaymentMethodCreditCardSecure(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    protected function createPaymentMethodSofort(): PaymentWithPostSaveOrderInterface
    {
        return new Sofort(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    protected function createPaymentMethodPaypalAuthorize(): PaymentWithPostSaveOrderInterface
    {
        return new PaypalAuthorize(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    protected function createPaymentMethodPaypalDebit(): PaymentWithPostSaveOrderInterface
    {
        return new PaypalDebit(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    protected function createPaymentMethodIdeal(): PaymentWithPostSaveOrderInterface
    {
        return new Ideal(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    protected function createPaymentMethodCreditCardSecure(): PaymentWithPostSaveOrderInterface
    {
        return new CreditCardSecure(
            $this->createTransactionLogReader(),
            $this->getConfig(),
            $this->createCreditCardRegistrationWriter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationWriterInterface
     */
    protected function createCreditCardRegistrationWriter(): RegistrationWriterInterface
    {
        return new RegistrationWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransactionInterface
     */
    protected function createExternalResponseTransaction(): ExternalResponseTransactionInterface
    {
        return new ExternalResponseTransaction(
            $this->createTransactionLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Encrypter\EncrypterInterface
     */
    protected function createAesEncrypter(): EncrypterInterface
    {
        return new AesEncrypter(
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesInterface
     */
    public function getSalesFacade(): HeidelpayToSalesInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyInterface
     */
    protected function getCurrencyFacade(): HeidelpayToCurrencyInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface
     */
    protected function getCreditCardPaymentMethodAdapter(): CreditCardPaymentInterface
    {
        return $this
            ->createAdapterFactory()
            ->createCreditCardPaymentMethodAdapter();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\QueryContainer\HeidelpayToSalesQueryContainerInterface
     */
    protected function getSalesQueryContainer(): HeidelpayToSalesQueryContainerInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyInterface
     */
    protected function getMoneyFacade(): HeidelpayToMoneyInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): HeidelpayToUtilEncodingServiceInterface
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
}
