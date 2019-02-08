<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConstants;

class ExternalResponseBuilder
{
    public const EMAIL = 'email';
    public const RESPONSE_URL = 'responseUrl';
    public const PAYMENT_BRAND = 'paymentBrand';
    public const AMOUNT = 'amount';

    public const FULL_NAME = 'fullName';
    public const PAYMENT_METHOD = 'paymentMethod';
    public const CUSTOMER_NAME = 'customerName';
    public const CUSTOMER_LAST_NAME = 'lastName';
    public const CUSTOMER_FULL_NAME = 'fullName';

    public const TRANSACRTION_CHANNEL = 'transactionChannel';
    public const SECURITY_SENDER = 'SECURITY_SENDER';
    public const USER_LOGIN = 'USER_LOGIN';
    public const USER_PWD = 'USER_PWD';
    public const TRANSACRTION_ID = 'TRANSACRTION_ID';

    public const CRITERION_SDK_NAME = 'HEIDELPAY:CRITERION_SDK_NAME';
    public const CRITERION_SDK_VALUE = "Heidelpay\PhpPaymentApi";

    public const BRAND_PROPERTY_NAME = 'brand';
    public const CRITERION_SECRET = 'HEIDELPAY:CRITERION_SECRET';

    public const PAYMENT_METHOD_CLASS_NAME = 'PaymentMethod';
    public const PROCESSING_RESULT = 'HEIDELPAY:PROCESSING_RESULT';

    public const SHA_512_ENCODE_ALGO = 'sha512';
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
    public function createHeidelpayResponse(string $paymentMethod): array
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
    protected function getPaymentBrand(string $paymentMethod): string
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
    protected function getPaymentMethod(string $paymentMethod): string
    {
        return mb_strtolower(preg_replace('~' . HeidelpayConfig::PROVIDER_NAME . '~', '', $paymentMethod));
    }

    /**
     * @param string $paymentMethodName
     *
     * @return string
     */
    protected function getClassName(string $paymentMethodName): string
    {
        $className = $paymentMethodName . self::PAYMENT_METHOD_CLASS_NAME;
        return ucfirst($className);
    }

    /**
     * @return string|null
     */
    protected function getProcessingResult(): ?string
    {
        return HeidelpayTestConstants::HEIDELPAY_SUCCESS_RESPONSE;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return int
     */
    protected function getTransationId(SpySalesOrder $orderEntity): int
    {
        return $orderEntity->getIdSalesOrder();
    }

    /**
     * @param int $identificationTransactionId
     * @param string $secret
     *
     * @return string
     */
    protected function getCriterionSecret(int $identificationTransactionId, string $secret): string
    {
        return hash(self::SHA_512_ENCODE_ALGO, $identificationTransactionId . $secret);
    }
}
