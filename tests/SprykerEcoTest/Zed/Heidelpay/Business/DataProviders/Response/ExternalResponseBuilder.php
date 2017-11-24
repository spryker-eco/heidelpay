<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response;

use ReflectionObject;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer\CustomerTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order\NewOrderWithOneItemTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order\OrderAddressTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\Action\HeidelpayResponseTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\PaymentHeidelpayTrait;

class ExternalResponseBuilder
{

    const EMAIL = 'email';
    const RESPONSE_URL = 'responseUrl';
    const PAYMENT_BRAND = 'paymentBrand';
    const AMOUNT = 'amount';

    const FULL_NAME = 'fullName';
    const PAYMENT_METHOD = 'paymentMethod';
    const CUSTOMER_NAME = 'customerName';
    const CUSTOMER_LAST_NAME = 'lastName';
    const CUSTOMER_FULL_NAME = 'fullName';

    const TRANSACRTION_CHANNEL = 'transactionChannel';
    const SECURITY_SENDER = 'SECURITY_SENDER';
    const USER_LOGIN = 'USER_LOGIN';
    const USER_PWD = 'USER_PWD';
    const TRANSACRTION_ID = 'TRANSACRTION_ID';

    const CRITERION_SDK_NAME = 'HEIDELPAY:CRITERION_SDK_NAME';
    const CRITERION_SDK_VALUE = "Heidelpay\PhpApi";

    const BRAND_PROPERTY_NAME = '_brand';
    const CRITERION_SECRET = 'HEIDELPAY:CRITERION_SECRET';

    const PAYMENT_METHOD_CLASS_NAME = 'PaymentMethod';
    const PROCESSING_RESULT = 'HEIDELPAY:PROCESSING_RESULT';
    const ACK = 'ACK';

    use HeidelpayResponseTrait, CustomerTrait, OrderAddressTrait, NewOrderWithOneItemTrait, PaymentHeidelpayTrait;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected $factory;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory $factory
     */
    public function __construct(HeidelpayBusinessFactory $factory)
    {
        $this->factory = $factory;
    }

    public function createHeidelpayResponse($paymentMethod)
    {
        $customerJohnDoe = $this->createOrGetCustomerJohnDoe();
        $billingAddressJohnDoe = $shippingAddressJohnDoe = $this->createOrderAddressJohnDoe();

        $orderEntity = $this->createOrderEntityWithItems(
            $customerJohnDoe,
            $billingAddressJohnDoe,
            $shippingAddressJohnDoe
        );

        $this->createHeidelpayPaymentEntity(
            $orderEntity,
            '',
            $paymentMethod
        );

        $config = $this->factory->getConfig();
        $param[static::EMAIL] = $customerJohnDoe->getEmail();
        $param[static::RESPONSE_URL] = $config->getZedResponseUrl();
        $param[static::CUSTOMER_NAME] = $customerJohnDoe->getFirstName();
        $param[static::CUSTOMER_FULL_NAME] = $customerJohnDoe->getFirstName();

        $param[static::AMOUNT] = $orderEntity->getLastOrderTotals()->getGrandTotal();

        $param[static::TRANSACRTION_ID] = $this->getTransationId($orderEntity);
        $param[static::TRANSACRTION_CHANNEL] = $config->getMerchantTransactionChannelByPaymentType($paymentMethod);
        $param[static::SECURITY_SENDER] = $config->getMerchantSecuritySender();
        $param[static::USER_LOGIN] = $config->getMerchantUserLogin();
        $param[static::USER_PWD] = $config->getMerchantUserPassword();

        $param[static::CRITERION_SDK_NAME] = static::CRITERION_SDK_VALUE;

        $param[static::CRITERION_SECRET] = $this->getCriterionSecret($this->getTransationId($orderEntity), $config->getApplicationSecret());

        $param[static::PAYMENT_BRAND] = $this->getPaymentBrand($paymentMethod);
        $param[static::PAYMENT_METHOD] = $this->getClassName($paymentMethod);

        $param[static::PROCESSING_RESULT] = $this->getProcessingResult();

        $response = $this->getHeidelpayResponseTemplate($param);

        return $response;
    }

    /**
     * @param string $paymentMethod
     *
     * @return mixed
     */
    protected function getPaymentBrand(string $paymentMethod)
    {
        $paymentMethodName = $this->getPaymentMethod($paymentMethod);
        $className = $this->getClassName($paymentMethodName);
        $fullClassName = static::CRITERION_SDK_VALUE . '\\PaymentMethods\\' . $className;

        $obj = new $fullClassName();
        $reflection = new ReflectionObject($obj);
        $property = $reflection->getProperty(self::BRAND_PROPERTY_NAME);
        $property->setAccessible(true);

        $brand = $property->getValue(new $fullClassName());
        return $brand;
    }

    /**
     * @param string $paymentMethod
     *
     * @return mixed
     */
    protected function getPaymentMethod(string $paymentMethod)
    {
        return mb_strtolower(preg_replace('~' . HeidelpayConfig::PROVIDER_NAME . '~', '', $paymentMethod));
    }

    /**
     * @param $paymentMethodName
     *
     * @return string
     */
    protected function getClassName($paymentMethodName)
    {
        $className = $paymentMethodName . self::PAYMENT_METHOD_CLASS_NAME;
        return ucfirst($className);
    }

    /**
     * @return string
     */
    protected function getProcessingResult()
    {
        return static::ACK;
    }

    /**
     * @param $orderEntity
     *
     * @return mixed
     */
    protected function getTransationId($orderEntity)
    {
        return $orderEntity->getIdSalesOrder();
    }

    /**
     * @param $identificationTransactionId
     * @param $secret
     *
     * @return string
     */
    protected function getCriterionSecret($identificationTransactionId, $secret)
    {
        return hash('sha512', $identificationTransactionId . $secret);
    }

}
