<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Zed\Heidelpay\Business\Adapter\AdapterFactory;
use SprykerEco\Zed\Heidelpay\Business\Hook\PostSaveHook;
use SprykerEco\Zed\Heidelpay\Business\Mapper\OrderToHeidelpayRequest;
use SprykerEco\Zed\Heidelpay\Business\Mapper\QuoteToHeidelpayRequest;
use SprykerEco\Zed\Heidelpay\Business\Order\OrderReader;
use SprykerEco\Zed\Heidelpay\Business\Order\Saver;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\LastSuccessfulRegistration;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\NewRegistrationIframe;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOptionsCalculator;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationReader;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationSaver;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationWriter;
use SprykerEco\Zed\Heidelpay\Business\Payment\CreditCardSecure;
use SprykerEco\Zed\Heidelpay\Business\Payment\Ideal;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReader;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriter;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaypalAuthorize;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaypalDebit;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilder;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilder;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilder;
use SprykerEco\Zed\Heidelpay\Business\Payment\Sofort;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\DebitTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransaction;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\AuthorizeTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\CaptureTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\DebitTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler\ExternalResponseTransactionHandler;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLogger;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReader;
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
    public function createAuthorizeTransactionHandler()
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
    public function createDebitTransactionHandler()
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
    public function createCaptureTransactionHandler()
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
    public function createExternalResponseTransactionHandler()
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
    public function createAdapterFactory()
    {
        return new AdapterFactory();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface
     */
    public function createTransactionLogger()
    {
        return new TransactionLogger(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Order\SaverInterface
     */
    public function createOrderSaver()
    {
        return new Saver($this->getPaymentMethodWithPreSavePaymentCollection());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Hook\PostSaveHookInterface
     */
    public function createPostSaveHook()
    {
        return new PostSaveHook($this->getPaymentMethodWithPostSaveOrderCollection());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReaderInterface
     */
    public function createPaymentReader()
    {
        return new PaymentReader($this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface
     */
    public function createPaymentWriter()
    {
        return new PaymentWriter($this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Order\OrderReaderInterface
     */
    public function createOrderReader()
    {
        return new OrderReader(
            $this->getSalesQueryContainer()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReaderInterface
     */
    public function createTransactionLogReader()
    {
        return new TransactionLogReader(
            $this->getQueryContainer(),
            $this->createAdapterFactory()->createTransactionParser(),
            $this->createOrderReader()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOptionsCalculatorInterface
     */
    public function createPaymentOptionsCalculator()
    {
        return new PaymentOptionsCalculator(
            $this->getCreditCardPaymentOptionsArray()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationReaderInterface
     */
    public function createCreditCardRegistrationReader()
    {
        return new RegistrationReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationSaverInterface
     */
    public function createCreditCardRegistrationSaver()
    {
        return new RegistrationSaver(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface[]
     */
    protected function getCreditCardPaymentOptionsArray()
    {
        return [
            $this->createLastSuccessfulRegistrationOption(),
            $this->createNewRegistrationIframeOption(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface
     */
    protected function createLastSuccessfulRegistrationOption()
    {
        return new LastSuccessfulRegistration(
            $this->createCreditCardRegistrationReader()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface
     */
    protected function createNewRegistrationIframeOption()
    {
        return new NewRegistrationIframe(
            $this->createAdapterRequestFromQuoteBuilder(),
            $this->getCreditCardPaymentMethodAdapter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilderInterface
     */
    protected function createExternalPaymentResponseBuilder()
    {
        return new ExternalPaymentResponseBuilder(
            $this->createPaymentReader()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface[]
     */
    protected function getAuthorizePaymentMethodAdapterCollection()
    {
        return $this
            ->createAdapterFactory()
            ->getAuthorizePaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface[]
     */
    protected function getCapturePaymentMethodAdapterCollection()
    {
        return $this
            ->createAdapterFactory()
            ->getCapturePaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface[]
     */
    protected function getDebitPaymentMethodAdapterCollection()
    {
        return $this
            ->createAdapterFactory()
            ->getDebitPaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface[]
     */
    protected function getExternalResponsePaymentMethodAdapterCollection()
    {
        return $this
            ->createAdapterFactory()
            ->getExternalResponsePaymentMethodAdapterCollection();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface
     */
    protected function createAdapterRequestFromOrderBuilder()
    {
        return new AdapterRequestFromOrderBuilder(
            $this->createOrderToHeidelpayRequestMapper(),
            $this->getCurrencyFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderInterface
     */
    protected function createAdapterRequestFromQuoteBuilder()
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
    protected function createOrderToHeidelpayRequestMapper()
    {
        return new OrderToHeidelpayRequest(
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Mapper\QuoteToHeidelpayRequestInterface
     */
    protected function createQuoteToHeidelpayRequestMapper()
    {
        return new QuoteToHeidelpayRequest(
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeTransactionInterface
     */
    protected function createAuthorizeTransaction()
    {
        return new AuthorizeTransaction(
            $this->createTransactionLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\DebitTransactionInterface
     */
    protected function createDebitTransaction()
    {
        return new DebitTransaction(
            $this->createTransactionLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransactionInterface
     */
    protected function createCaptureTransaction()
    {
        return new CaptureTransaction(
            $this->createTransactionLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface[]
     */
    protected function getPaymentMethodWithPostSaveOrderCollection()
    {
        return [
            HeidelpayConstants::PAYMENT_METHOD_SOFORT => $this->createPaymentMethodSofort(),
            HeidelpayConstants::PAYMENT_METHOD_PAYPAL_AUTHORIZE => $this->createPaymentMethodPaypalAuthorize(),
            HeidelpayConstants::PAYMENT_METHOD_PAYPAL_DEBIT => $this->createPaymentMethodPaypalDebit(),
            HeidelpayConstants::PAYMENT_METHOD_IDEAL => $this->createPaymentMethodIdeal(),
            HeidelpayConstants::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createPaymentMethodCreditCardSecure(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPreSavePaymentInterface[]
     */
    protected function getPaymentMethodWithPreSavePaymentCollection()
    {
        return [
            HeidelpayConstants::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createPaymentMethodCreditCardSecure(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    protected function createPaymentMethodSofort()
    {
        return new Sofort(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    protected function createPaymentMethodPaypalAuthorize()
    {
        return new PaypalAuthorize(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    protected function createPaymentMethodPaypalDebit()
    {
        return new PaypalDebit(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    protected function createPaymentMethodIdeal()
    {
        return new Ideal(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface
     */
    protected function createPaymentMethodCreditCardSecure()
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
    protected function createCreditCardRegistrationWriter()
    {
        return new RegistrationWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransactionInterface
     */
    protected function createExternalResponseTransaction()
    {
        return new ExternalResponseTransaction(
            $this->createTransactionLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesInterface
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyInterface
     */
    protected function getCurrencyFacade()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface
     */
    protected function getCreditCardPaymentMethodAdapter()
    {
        return $this
            ->createAdapterFactory()
            ->createCreditCardPaymentMethodAdapter();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\QueryContainer\HeidelpayToSalesQueryContainerInterface
     */
    protected function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyInterface
     */
    protected function getMoneyFacade()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
