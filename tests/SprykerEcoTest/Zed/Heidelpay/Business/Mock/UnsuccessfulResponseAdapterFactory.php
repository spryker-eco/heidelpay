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
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods\UnsuccessfulEasyCreditPaymentMock;

class UnsuccessfulResponseAdapterFactory extends AdapterFactory
{
    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface[]
     */
    public function getAuthorizePaymentMethodAdapterCollection()
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_SOFORT => $this->createSofortPaymentMethodAdapter(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface[]
     */
    public function getAuthorizeOnRegistrationPaymentMethodAdapterCollection()
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => $this->createEasyCreditPaymentMethodAdapter(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface[]
     */
    public function getDebitPaymentMethodAdapterCollection()
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_PAYPAL_DEBIT => $this->createPaypalPaymentMethodAdapter(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface[]
     */
    public function getCapturePaymentMethodAdapterCollection()
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createCreditCardPaymentMethodAdapter(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface[]
     */
    public function getInitializePaymentMethodAdapterCollection()
    {
        return [
            HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => $this->createEasyCreditPaymentMethodAdapter(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPaymentInterface
     */
    public function createSofortPaymentMethodAdapter()
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
    public function createPaypalPaymentMethodAdapter()
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
    public function createCreditCardPaymentMethodAdapter()
    {
        return new UnsuccessfulCreditCardCapturePaymentMock(
            $this->createRequestToHeidelpayMapper(),
            $this->createResponseFromHeidelpayMapper(),
            $this->getHeidelpayConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\EasyCreditPaymentInterface
     */
    public function createEasyCreditPaymentMethodAdapter()
    {
        return new UnsuccessfulEasyCreditPaymentMock(
            $this->createRequestToHeidelpayMapper(),
            $this->createResponseFromHeidelpayMapper(),
            $this->getHeidelpayConfig()
        );
    }
}
