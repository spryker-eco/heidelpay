<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer\CustomerTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order\NewOrderWithOneItemTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order\OrderAddressTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\PaymentHeidelpayTrait;

class PaymentBuilder
{
    use CustomerTrait;
    use OrderAddressTrait;
    use NewOrderWithOneItemTrait;
    use PaymentHeidelpayTrait;

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
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function createPayment(string $paymentMethod): SpySalesOrder
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
            HeidelpayTestConfig::HEIDELPAY_REFERENCE,
            $paymentMethod
        );

        return $orderEntity;
    }
}
