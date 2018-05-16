<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use ReflectionObject;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer\CustomerTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order\NewOrderWithOneItemTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order\OrderAddressTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\Action\HeidelpayResponseTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\PaymentHeidelpayTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\HeidelpayTestConstants;

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
    const CRITERION_SDK_VALUE = "Heidelpay\PhpPaymentApi";

    const BRAND_PROPERTY_NAME = 'brand';
    const CRITERION_SECRET = 'HEIDELPAY:CRITERION_SECRET';

    const PAYMENT_METHOD_CLASS_NAME = 'PaymentMethod';
    const PROCESSING_RESULT = 'HEIDELPAY:PROCESSING_RESULT';

    const SHA_512_ENCODE_ALGO = 'sha512';
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

    /**
     * @param string $paymentMethod
     *
     * @return array
     */
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
        $responseParam[static::EMAIL] = $customerJohnDoe->getEmail();
        $responseParam[static::RESPONSE_URL] = $config->getZedResponseUrl();
        $responseParam[static::CUSTOMER_NAME] = $customerJohnDoe->getFirstName();
        $responseParam[static::CUSTOMER_FULL_NAME] = $customerJohnDoe->getFirstName();

        $responseParam[static::AMOUNT] = $orderEntity->getLastOrderTotals()->getGrandTotal();

        $responseParam[static::TRANSACRTION_ID] = $this->getTransationId($orderEntity);
        $responseParam[static::TRANSACRTION_CHANNEL] = $config->getMerchantTransactionChannelByPaymentType($paymentMethod);
        $responseParam[static::SECURITY_SENDER] = $config->getMerchantSecuritySender();
        $responseParam[static::USER_LOGIN] = $config->getMerchantUserLogin();
        $responseParam[static::USER_PWD] = $config->getMerchantUserPassword();

        $responseParam[static::CRITERION_SDK_NAME] = static::CRITERION_SDK_VALUE;

        $responseParam[static::CRITERION_SECRET] = $this->getCriterionSecret($this->getTransationId($orderEntity), $config->getApplicationSecret());

        $responseParam[static::PAYMENT_BRAND] = $this->getPaymentBrand($paymentMethod);
        $responseParam[static::PAYMENT_METHOD] = $this->getClassName($paymentMethod);

        $responseParam[static::PROCESSING_RESULT] = $this->getProcessingResult();

        $response = $this->getHeidelpayResponseTemplate($responseParam);

        return $response;
    }

    /**
     * @param string $paymentMethod
     *
     * @return string $brand
     */
    protected function getPaymentBrand($paymentMethod)
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
     * @return string
     */
    protected function getPaymentMethod($paymentMethod)
    {
        return mb_strtolower(preg_replace('~' . HeidelpayConfig::PROVIDER_NAME . '~', '', $paymentMethod));
    }

    /**
     * @param string $paymentMethodName
     *
     * @return string
     */
    protected function getClassName($paymentMethodName)
    {
        $className = $paymentMethodName . self::PAYMENT_METHOD_CLASS_NAME;
        return ucfirst($className);
    }

    /**
     * @return string|null
     */
    protected function getProcessingResult()
    {
        return HeidelpayTestConstants::HEIDELPAY_SUCCESS_RESPONSE;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return int
     */
    protected function getTransationId(SpySalesOrder $orderEntity)
    {
        return $orderEntity->getIdSalesOrder();
    }

    /**
     * @param int $identificationTransactionId
     * @param string $secret
     *
     * @return string
     */
    protected function getCriterionSecret($identificationTransactionId, $secret)
    {
        return hash(self::SHA_512_ENCODE_ALGO, $identificationTransactionId . $secret);
    }
}
