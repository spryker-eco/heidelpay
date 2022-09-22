<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Basket\Basket;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Basket\BasketInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\BasketRequestToHeidelpay;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\BasketRequestToHeidelpayInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\BasketResponseFromHeidelpay;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\BasketResponseFromHeidelpayInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\RequestToHeidelpay;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\RequestToHeidelpayInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\ResponseFromHeidelpay;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\ResponseFromHeidelpayInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\ResponsePayloadToApiResponse;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\ResponsePayloadToApiResponseInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPayment;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\DirectDebitPayment;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\DirectDebitPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\EasyCreditPayment;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\EasyCreditPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\IdealPayment;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\IdealPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\InvoiceSecuredB2cPayment;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\InvoiceSecuredB2cPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\PaypalPayment;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\PaypalPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPayment;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPaymentInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayConfigInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayDependencyProvider;

/**
 * @method \SprykerEco\Zed\Heidelpay\HeidelpayConfig getConfig()
 */
class AdapterFactory extends AbstractBusinessFactory implements AdapterFactoryInterface
{
    /**
     * @return array<string, \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface|\SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\EasyCreditPaymentInterface|\SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\IdealPaymentInterface|\SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\InvoiceSecuredB2cPaymentInterface|\SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\PaypalPaymentInterface|\SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPaymentInterface>
     */
    public function getAuthorizePaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_SOFORT => $this->createSofortPaymentMethodAdapter(),
            HeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE => $this->createPaypalPaymentMethodAdapter(),
            HeidelpayConfig::PAYMENT_METHOD_IDEAL => $this->createIdealPaymentMethodAdapter(),
            HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => $this->createEasyCreditPaymentMethodAdapter(),
            HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createCreditCardPaymentMethodAdapter(),
            HeidelpayConfig::PAYMENT_METHOD_INVOICE_SECURED_B2C => $this->createInvoiceSecuredB2cPayment(),
        ];
    }

    /**
     * @return array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeOnRegistrationInterface>
     */
    public function getAuthorizeOnRegistrationPaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => $this->createEasyCreditPaymentMethodAdapter(),
        ];
    }

    /**
     * @return array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithInitializeInterface>
     */
    public function getInitializePaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => $this->createEasyCreditPaymentMethodAdapter(),
        ];
    }

    /**
     * @return array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithReservationInterface>
     */
    public function getReservationPaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => $this->createEasyCreditPaymentMethodAdapter(),
        ];
    }

    /**
     * @return array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithRefundInterface>
     */
    public function getRefundPaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_DIRECT_DEBIT => $this->createDirectDebitPaymentMethod(),
        ];
    }

    /**
     * @return array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithFinalizeInterface>
     */
    public function getFinalizePaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => $this->createEasyCreditPaymentMethodAdapter(),
            HeidelpayConfig::PAYMENT_METHOD_INVOICE_SECURED_B2C => $this->createInvoiceSecuredB2cPayment(),
        ];
    }

    /**
     * @return array<string, \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface|\SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\EasyCreditPaymentInterface|\SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\PaypalPaymentInterface>
     */
    public function getCapturePaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE => $this->createPaypalPaymentMethodAdapter(),
            HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => $this->createEasyCreditPaymentMethodAdapter(),
            HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createCreditCardPaymentMethodAdapter(),
        ];
    }

    /**
     * @return array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface>
     */
    public function getDebitPaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_PAYPAL_DEBIT => $this->createPaypalPaymentMethodAdapter(),
        ];
    }

    /**
     * @return array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitOnRegistrationInterface>
     */
    public function getDebitOnRegistrationPaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_DIRECT_DEBIT => $this->createDirectDebitPaymentMethod(),
        ];
    }

    /**
     * @return array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface>
     */
    public function getExternalResponsePaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_SOFORT => $this->createSofortPaymentMethodAdapter(),
            HeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE => $this->createPaypalPaymentMethodAdapter(),
            HeidelpayConfig::PAYMENT_METHOD_PAYPAL_DEBIT => $this->createPaypalPaymentMethodAdapter(),
            HeidelpayConfig::PAYMENT_METHOD_IDEAL => $this->createIdealPaymentMethodAdapter(),
            HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => $this->createEasyCreditPaymentMethodAdapter(),
            HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createCreditCardPaymentMethodAdapter(),
            HeidelpayConfig::PAYMENT_METHOD_INVOICE_SECURED_B2C => $this->createInvoiceSecuredB2cPayment(),
            HeidelpayConfig::PAYMENT_METHOD_DIRECT_DEBIT => $this->createDirectDebitPaymentMethod(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Basket\BasketInterface
     */
    public function createBasketAdapter(): BasketInterface
    {
        return new Basket(
            $this->createBasketRequestToHeidelpayMapper(),
            $this->createBasketResponseFromHeidelpayMapper(),
            $this->getHeidelpayConfig(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPaymentInterface
     */
    public function createSofortPaymentMethodAdapter(): SofortPaymentInterface
    {
        return new SofortPayment(
            $this->createRequestToHeidelpayMapper(),
            $this->createResponseFromHeidelpayMapper(),
            $this->getHeidelpayConfig(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\IdealPaymentInterface
     */
    public function createIdealPaymentMethodAdapter(): IdealPaymentInterface
    {
        return new IdealPayment(
            $this->createRequestToHeidelpayMapper(),
            $this->createResponseFromHeidelpayMapper(),
            $this->getHeidelpayConfig(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\EasyCreditPaymentInterface
     */
    public function createEasyCreditPaymentMethodAdapter(): EasyCreditPaymentInterface
    {
        return new EasyCreditPayment(
            $this->createRequestToHeidelpayMapper(),
            $this->createResponseFromHeidelpayMapper(),
            $this->getHeidelpayConfig(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\PaypalPaymentInterface
     */
    public function createPaypalPaymentMethodAdapter(): PaypalPaymentInterface
    {
        return new PaypalPayment(
            $this->createRequestToHeidelpayMapper(),
            $this->createResponseFromHeidelpayMapper(),
            $this->getHeidelpayConfig(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface
     */
    public function createCreditCardPaymentMethodAdapter(): CreditCardPaymentInterface
    {
        return new CreditCardPayment(
            $this->createRequestToHeidelpayMapper(),
            $this->createResponseFromHeidelpayMapper(),
            $this->getHeidelpayConfig(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\InvoiceSecuredB2cPaymentInterface
     */
    public function createInvoiceSecuredB2cPayment(): InvoiceSecuredB2cPaymentInterface
    {
        return new InvoiceSecuredB2cPayment(
            $this->createRequestToHeidelpayMapper(),
            $this->createResponseFromHeidelpayMapper(),
            $this->getHeidelpayConfig(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\DirectDebitPaymentInterface
     */
    public function createDirectDebitPaymentMethod(): DirectDebitPaymentInterface
    {
        return new DirectDebitPayment(
            $this->createRequestToHeidelpayMapper(),
            $this->createResponseFromHeidelpayMapper(),
            $this->getHeidelpayConfig(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\TransactionParserInterface
     */
    public function createTransactionParser(): TransactionParserInterface
    {
        return new TransactionParser(
            $this->createResponseFromHeidelpayMapper(),
            $this->createResponsePayloadToApiResponseMapper(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\ResponsePayloadToApiResponseInterface
     */
    protected function createResponsePayloadToApiResponseMapper(): ResponsePayloadToApiResponseInterface
    {
        return new ResponsePayloadToApiResponse(
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\RequestToHeidelpayInterface
     */
    protected function createRequestToHeidelpayMapper(): RequestToHeidelpayInterface
    {
        return new RequestToHeidelpay();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\BasketRequestToHeidelpayInterface
     */
    protected function createBasketRequestToHeidelpayMapper(): BasketRequestToHeidelpayInterface
    {
        return new BasketRequestToHeidelpay(
            $this->getHeidelpayConfig(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\ResponseFromHeidelpayInterface
     */
    protected function createResponseFromHeidelpayMapper(): ResponseFromHeidelpayInterface
    {
        return new ResponseFromHeidelpay(
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\BasketResponseFromHeidelpayInterface
     */
    protected function createBasketResponseFromHeidelpayMapper(): BasketResponseFromHeidelpayInterface
    {
        return new BasketResponseFromHeidelpay();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\HeidelpayConfigInterface
     */
    protected function getHeidelpayConfig(): HeidelpayConfigInterface
    {
        return $this->getConfig();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): HeidelpayToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
