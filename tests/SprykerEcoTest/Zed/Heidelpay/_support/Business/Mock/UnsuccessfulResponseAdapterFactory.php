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
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods\UnsuccessfulCreditCardCapturePaymentMock;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods\UnsuccessfulDirectDebitPaymentMock;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods\UnsuccessfulEasyCreditPaymentMock;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods\UnsuccessfulPaypalDebitPaymentMock;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods\UnsuccessfulSofortPaymentMock;

class UnsuccessfulResponseAdapterFactory extends AdapterFactory
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
     * @return array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface>
     */
    public function getAuthorizeOnRegistrationPaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => $this->createEasyCreditPaymentMethodAdapter(),
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
     * @return array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface>
     */
    public function getCapturePaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createCreditCardPaymentMethodAdapter(),
        ];
    }

    /**
     * @return array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface>
     */
    public function getInitializePaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => $this->createEasyCreditPaymentMethodAdapter(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPaymentInterface
     */
    public function createSofortPaymentMethodAdapter(): SofortPaymentInterface
    {
        return new UnsuccessfulSofortPaymentMock(
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
        return new UnsuccessfulPaypalDebitPaymentMock(
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
        return new UnsuccessfulCreditCardCapturePaymentMock(
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
        return new UnsuccessfulEasyCreditPaymentMock(
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
        return new UnsuccessfulDirectDebitPaymentMock(
            $this->createRequestToHeidelpayMapper(),
            $this->createResponseFromHeidelpayMapper(),
            $this->getHeidelpayConfig(),
        );
    }
}
