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
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer\CustomerTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order\NewOrderWithOneItemTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order\OrderAddressTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\PaymentHeidelpayTrait;

abstract class ExternalResponseBuilder
{
    public const EMAIL = 'email';
    public const RESPONSE_URL = 'responseUrl';
    public const PAYMENT_BRAND = 'paymentBrand';
    public const AMOUNT = 'amount';

    public const FULL_NAME = 'fullName';
    public const PAYMENT_METHOD = 'paymentMethod';
    public const CUSTOMER_NAME = 'customerName';
    public const CUSTOMER_FULL_NAME = 'fullName';
    public const TRANSACRTION_CHANNEL = 'transactionChannel';

    public const SECURITY_SENDER = 'SECURITY_SENDER';
    public const USER_LOGIN = 'USER_LOGIN';
    public const USER_PWD = 'USER_PWD';
    public const TRANSACRTION_ID = 'TRANSACRTION_ID';
    public const IDENTIFICATION_UNIQUEID = 'IDENTIFICATION_UNIQUEID';
    public const PROCESSING_RESULT = 'PROCESSING_RESULT';
    public const PROCESSING_RETURN = 'PROCESSING_RETURN';
    public const PAYMENT_CODE = 'PAYMENT_CODE';
    public const PROCESSING_CODE = 'PROCESSING_CODE';
    public const PROCESSING_STATUS_CODE = 'PROCESSING_STATUS_CODE';
    public const PROCESSING_REASON_CODE = 'PROCESSING_REASON_CODE';

    public const CRITERION_SDK_NAME = 'HEIDELPAY:CRITERION_SDK_NAME';
    public const CRITERION_SDK_VALUE = "Heidelpay\\PhpPaymentApi";

    public const BRAND_PROPERTY_NAME = 'brand';
    public const CRITERION_SECRET = 'HEIDELPAY:CRITERION_SECRET';

    public const PAYMENT_METHOD_CLASS_NAME = 'PaymentMethod';

    public const SHA_512_ENCODE_ALGO = 'sha512';

    public const PAYMENT_CODE_HP_INI = 'HP.INI';
    public const PAYMENT_CODE_HP_PI = 'HP.PI';
    public const PAYMENT_CODE_HP_FI = 'HP.FI';

    use HeidelpayResponseTrait,
        CustomerTrait,
        OrderAddressTrait,
        NewOrderWithOneItemTrait,
        PaymentHeidelpayTrait;

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
    abstract public function createHeidelpayResponse(string $paymentMethod): array;

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
        $paymentMethod = preg_replace(
            '~' . HeidelpayConfig::PROVIDER_NAME . '~',
            '',
            $paymentMethod
        );

        return ucfirst($paymentMethod);
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
        return HeidelpayTestConfig::HEIDELPAY_SUCCESS_RESPONSE;
    }

    /**
     * @return string
     */
    protected function getProcessingReturn(): string
    {
        return "Request successfully processed in 'Merchant in Connector Test Mode'";
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
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return string
     */
    protected function getIdentificationUniqueId(SpySalesOrder $orderEntity): string
    {
        return '31HA07BC814A66978BC90CB3EF663058';
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
