<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business;

use Spryker\Shared\Heidelpay\HeidelpayConstants;
use Spryker\Zed\Heidelpay\Business\Adapter\AdapterFactory;
use Spryker\Zed\Heidelpay\Business\Hook\PostSaveHook;
use Spryker\Zed\Heidelpay\Business\Mapper\OrderToHeidelpayRequest;
use Spryker\Zed\Heidelpay\Business\Mapper\QuoteToHeidelpayRequest;
use Spryker\Zed\Heidelpay\Business\Order\OrderReader;
use Spryker\Zed\Heidelpay\Business\Order\Saver;
use Spryker\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\LastSuccessfulRegistration;
use Spryker\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\NewRegistrationIframe;
use Spryker\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOptionsCalculator;
use Spryker\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationReader;
use Spryker\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationWriter;
use Spryker\Zed\Heidelpay\Business\Payment\CreditCardSecure;
use Spryker\Zed\Heidelpay\Business\Payment\Ideal;
use Spryker\Zed\Heidelpay\Business\Payment\PaymentReader;
use Spryker\Zed\Heidelpay\Business\Payment\PaymentWriter;
use Spryker\Zed\Heidelpay\Business\Payment\PaypalAuthorize;
use Spryker\Zed\Heidelpay\Business\Payment\PaypalDebit;
use Spryker\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilder;
use Spryker\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilder;
use Spryker\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilder;
use Spryker\Zed\Heidelpay\Business\Payment\Sofort;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeTransaction;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransaction;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\DebitTransaction;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransaction;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\Handler\AuthorizeTransactionHandler;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\Handler\CaptureTransactionHandler;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\Handler\DebitTransactionHandler;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\Handler\ExternalResponseTransactionHandler;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLogger;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReader;
use Spryker\Zed\Heidelpay\HeidelpayDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Heidelpay\HeidelpayConfig getConfig()
 */
class HeidelpayBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Transaction\Handler\AuthorizeTransactionHandlerInterface
     */
    public function createAuthorizeTransactionHandler()
    {
        return new AuthorizeTransactionHandler(
            $this->createAuthorizeTransaction(),
            $this->createAuthorizePaymentMethodAdapterCollection(),
            $this->createAdapterRequestFromOrderBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Transaction\Handler\DebitTransactionHandlerInterface
     */
    public function createDebitTransactionHandler()
    {
        return new DebitTransactionHandler(
            $this->createDebitTransaction(),
            $this->createDebitPaymentMethodAdapterCollection(),
            $this->createAdapterRequestFromOrderBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Transaction\Handler\CaptureTransactionHandlerInterface
     */
    public function createCaptureTransactionHandler()
    {
        return new CaptureTransactionHandler(
            $this->createCaptureTransaction(),
            $this->createCapturePaymentMethodAdapterCollection(),
            $this->createAdapterRequestFromOrderBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Transaction\Handler\ExternalResponseTransactionHandlerInterface
     */
    public function createExternalResponseTransactionHandler()
    {
        return new ExternalResponseTransactionHandler(
            $this->createExternalResponseTransaction(),
            $this->createExternalResponsePaymentMethodAdapterCollection(),
            $this->createExternalPaymentResponseBuilder(),
            $this->createPaymentWriter()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Adapter\AdapterFactory
     */
    public function createAdapterFactory()
    {
        return new AdapterFactory();
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface
     */
    public function createTransactionLogger()
    {
        return new TransactionLogger(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Order\SaverInterface
     */
    public function createOrderSaver()
    {
        return new Saver($this->createPaymentMethodWithPreSavePaymentCollection());
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Hook\PostSaveHookInterface
     */
    public function createPostSaveHook()
    {
        return new PostSaveHook($this->createPaymentMethodWithPostSaveOrderCollection());
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\PaymentReaderInterface
     */
    public function createPaymentReader()
    {
        return new PaymentReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\PaymentWriterInterface
     */
    public function createPaymentWriter()
    {
        return new PaymentWriter($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Order\OrderReaderInterface
     */
    public function createOrderReader()
    {
        return new OrderReader(
            $this->getSalesQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReaderInterface
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
     * @return \Spryker\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOptionsCalculatorInterface
     */
    public function createPaymentOptionsCalculator()
    {
        return new PaymentOptionsCalculator(
            $this->createCreditCardPaymentOptionsArray()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface[]
     */
    protected function createCreditCardPaymentOptionsArray()
    {
        return [
            $this->createLastSuccessfulRegistrationOption(),
            $this->createNewRegistrationIframeOption(),
        ];
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface
     */
    protected function createLastSuccessfulRegistrationOption()
    {
        return new LastSuccessfulRegistration(
            $this->createCreditCardRegistrationReader()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface
     */
    protected function createNewRegistrationIframeOption()
    {
        return new NewRegistrationIframe(
            $this->createAdapterRequestFromQuoteBuilder(),
            $this->getCreditCardPaymentMethodAdapter()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilderInterface
     */
    protected function createExternalPaymentResponseBuilder()
    {
        return new ExternalPaymentResponseBuilder(
            $this->createPaymentReader()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface[]
     */
    protected function createAuthorizePaymentMethodAdapterCollection()
    {
        return $this
            ->createAdapterFactory()
            ->createAuthorizePaymentMethodAdapterCollection();
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface[]
     */
    protected function createCapturePaymentMethodAdapterCollection()
    {
        return $this
            ->createAdapterFactory()
            ->createCapturePaymentMethodAdapterCollection();
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface[]
     */
    protected function createDebitPaymentMethodAdapterCollection()
    {
        return $this
            ->createAdapterFactory()
            ->createDebitPaymentMethodAdapterCollection();
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface[]
     */
    protected function createExternalResponsePaymentMethodAdapterCollection()
    {
        return $this
            ->createAdapterFactory()
            ->createExternalResponsePaymentMethodAdapterCollection();
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface
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
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderInterface
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
     * @return \Spryker\Zed\Heidelpay\Business\Mapper\OrderToHeidelpayRequestInterface
     */
    protected function createOrderToHeidelpayRequestMapper()
    {
        return new OrderToHeidelpayRequest(
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Mapper\QuoteToHeidelpayRequestInterface
     */
    protected function createQuoteToHeidelpayRequestMapper()
    {
        return new QuoteToHeidelpayRequest(
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeTransactionInterface
     */
    protected function createAuthorizeTransaction()
    {
        return new AuthorizeTransaction(
            $this->createTransactionLogger()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Transaction\DebitTransactionInterface
     */
    protected function createDebitTransaction()
    {
        return new DebitTransaction(
            $this->createTransactionLogger()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransactionInterface
     */
    protected function createCaptureTransaction()
    {
        return new CaptureTransaction(
            $this->createTransactionLogger()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface[]
     */
    protected function createPaymentMethodWithPostSaveOrderCollection()
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
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithPreSavePaymentInterface[]
     */
    protected function createPaymentMethodWithPreSavePaymentCollection()
    {
        return [
            HeidelpayConstants::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createPaymentMethodCreditCardSecure(),
        ];
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Sofort
     */
    protected function createPaymentMethodSofort()
    {
        return new Sofort(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\PaypalAuthorize
     */
    protected function createPaymentMethodPaypalAuthorize()
    {
        return new PaypalAuthorize(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\PaypalDebit
     */
    protected function createPaymentMethodPaypalDebit()
    {
        return new PaypalDebit(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Ideal
     */
    protected function createPaymentMethodIdeal()
    {
        return new Ideal(
            $this->createTransactionLogReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\CreditCardSecure
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
     * @return \Spryker\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationReaderInterface
     */
    protected function createCreditCardRegistrationReader()
    {
        return new RegistrationReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationWriterInterface
     */
    protected function createCreditCardRegistrationWriter()
    {
        return new RegistrationWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransactionInterface
     */
    protected function createExternalResponseTransaction()
    {
        return new ExternalResponseTransaction(
            $this->createTransactionLogger()
        );
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesInterface
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyInterface
     */
    protected function getCurrencyFacade()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface
     */
    protected function getCreditCardPaymentMethodAdapter()
    {
        return $this
            ->createAdapterFactory()
            ->createCreditCardPaymentMethodAdapter();
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Dependency\QueryContainer\HeidelpayToSalesQueryContainerInterface
     */
    protected function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyInterface
     */
    protected function getMoneyFacade()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::SERVICE_UTIL_ENCODING);
    }

}
