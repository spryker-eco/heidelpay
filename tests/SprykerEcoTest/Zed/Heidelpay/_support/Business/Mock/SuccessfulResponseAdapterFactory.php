<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock;

use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\Adapter\AdapterFactory;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\DirectDebitPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\EasyCreditPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\PaypalPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPaymentInterface;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods\SuccessfulCreditCardCapturePaymentMock;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods\SuccessfulDirectDebitPaymentMock;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods\SuccessfulEasyCreditPaymentMock;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods\SuccessfulPaypalDebitPaymentMock;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods\SuccessfulSofortPaymentMock;

class SuccessfulResponseAdapterFactory extends AdapterFactory
{
    /**
     * @return array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface>
     */
    public function getAuthorizePaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_SOFORT => $this->createSofortPaymentMethodAdapter(),
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
     * @return array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithInitializeInterface>
     */
    public function getInitializePaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => $this->createEasyCreditPaymentMethodAdapter(),
        ];
    }

    /**
     * @return array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface>
     */
    public function getCapturePaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createCreditCardPaymentMethodAdapter(),
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
     * @return array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitOnRegistrationInterface>
     */
    public function getRefundPaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_DIRECT_DEBIT => $this->createDirectDebitPaymentMethod(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPaymentInterface
     */
    public function createSofortPaymentMethodAdapter(): SofortPaymentInterface
    {
        return new SuccessfulSofortPaymentMock(
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
        return new SuccessfulPaypalDebitPaymentMock(
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
        return new SuccessfulCreditCardCapturePaymentMock(
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
        return new SuccessfulEasyCreditPaymentMock(
            $this->createRequestToHeidelpayMapper(),
            $this->createResponseFromHeidelpayMapper(),
            $this->getHeidelpayConfig(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\EasyCreditPaymentInterface
     */
    public function createEasyCreditInitializePaymentMethodAdapter(): EasyCreditPaymentInterface
    {
        return new SuccessfulEasyCreditPaymentMock(
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
        return new SuccessfulDirectDebitPaymentMock(
            $this->createRequestToHeidelpayMapper(),
            $this->createResponseFromHeidelpayMapper(),
            $this->getHeidelpayConfig(),
        );
    }
}
