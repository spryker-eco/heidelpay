<?php
/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\RequestToHeidelpay;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\ResponseFromHeidelpay;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\ResponsePayloadToApiResponse;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPayment;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\IdealPayment;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\PaypalPayment;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPayment;
use SprykerEco\Zed\Heidelpay\HeidelpayDependencyProvider;

/**
 * @method \SprykerEco\Zed\Heidelpay\HeidelpayConfig getConfig()
 */
class AdapterFactory extends AbstractBusinessFactory
{

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface[]
     */
    public function getAuthorizePaymentMethodAdapterCollection()
    {
        return [
            HeidelpayConstants::PAYMENT_METHOD_SOFORT => $this->createSofortPaymentMethodAdapter(),
            HeidelpayConstants::PAYMENT_METHOD_PAYPAL_AUTHORIZE => $this->createPaypalPaymentMethodAdapter(),
            HeidelpayConstants::PAYMENT_METHOD_IDEAL => $this->createIdealPaymentMethodAdapter(),
            HeidelpayConstants::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createCreditCardPaymentMethodAdapter(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface[]
     */
    public function getCapturePaymentMethodAdapterCollection()
    {
        return [
            HeidelpayConstants::PAYMENT_METHOD_PAYPAL_AUTHORIZE => $this->createPaypalPaymentMethodAdapter(),
            HeidelpayConstants::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createCreditCardPaymentMethodAdapter(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface[]
     */
    public function getDebitPaymentMethodAdapterCollection()
    {
        return [
            HeidelpayConstants::PAYMENT_METHOD_PAYPAL_DEBIT => $this->createPaypalPaymentMethodAdapter(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface[]
     */
    public function getExternalResponsePaymentMethodAdapterCollection()
    {
        return [
            HeidelpayConstants::PAYMENT_METHOD_SOFORT => $this->createSofortPaymentMethodAdapter(),
            HeidelpayConstants::PAYMENT_METHOD_PAYPAL_AUTHORIZE => $this->createPaypalPaymentMethodAdapter(),
            HeidelpayConstants::PAYMENT_METHOD_PAYPAL_DEBIT => $this->createPaypalPaymentMethodAdapter(),
            HeidelpayConstants::PAYMENT_METHOD_IDEAL => $this->createIdealPaymentMethodAdapter(),
            HeidelpayConstants::PAYMENT_METHOD_CREDIT_CARD_SECURE => $this->createCreditCardPaymentMethodAdapter(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPaymentInterface
     */
    public function createSofortPaymentMethodAdapter()
    {
        return new SofortPayment(
            $this->createRequestToHeidelpayMapper(),
            $this->createResponseFromHeidelpayMapper(),
            $this->getHeidelpayConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\IdealPaymentInterface
     */
    public function createIdealPaymentMethodAdapter()
    {
        return new IdealPayment(
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
        return new PaypalPayment(
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
        return new CreditCardPayment(
            $this->createRequestToHeidelpayMapper(),
            $this->createResponseFromHeidelpayMapper(),
            $this->getHeidelpayConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\TransactionParser
     */
    public function createTransactionParser()
    {
        return new TransactionParser(
            $this->createResponseFromHeidelpayMapper(),
            $this->createResponsePayloadToApiResponseMapper()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\ResponsePayloadToApiResponse
     */
    protected function createResponsePayloadToApiResponseMapper()
    {
        return new ResponsePayloadToApiResponse(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\RequestToHeidelpayInterface
     */
    protected function createRequestToHeidelpayMapper()
    {
        return new RequestToHeidelpay();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\ResponseFromHeidelpayInterface
     */
    protected function createResponseFromHeidelpayMapper()
    {
        return new ResponseFromHeidelpay(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\HeidelpayConfig
     */
    protected function getHeidelpayConfig()
    {
        return $this->getConfig();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::SERVICE_UTIL_ENCODING);
    }

}
