<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock;

use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\Adapter\AdapterFactory;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods\UnsuccessfulCreditCardCapturePaymentMock;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods\UnsuccessfulPaypalDebitPaymentMock;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods\UnsuccessfulSofortPaymentMock;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\PaypalPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface;

class UnsuccessfulResponseAdapterFactory extends AdapterFactory
{
    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface[]
     */
    public function getAuthorizePaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_SOFORT => $this->createSofortPaymentMethodAdapter(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface[]
     */
    public function getDebitPaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_PAYPAL_DEBIT => $this->createPaypalPaymentMethodAdapter(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface[]
     */
    public function getCapturePaymentMethodAdapterCollection(): array
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createCreditCardPaymentMethodAdapter(),
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
            $this->getHeidelpayConfig()
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
            $this->getHeidelpayConfig()
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
            $this->getHeidelpayConfig()
        );
    }
}
