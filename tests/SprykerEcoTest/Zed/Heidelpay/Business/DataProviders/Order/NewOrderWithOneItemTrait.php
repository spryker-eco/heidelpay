<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order;

use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;

trait NewOrderWithOneItemTrait
{
    /**
     * @var string
     */
    private $uniqueOrderItemState;

    /**
     * @var string
     */
    private $uniqueOmsProcess;

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $billingAddress
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $shippingAddress
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createOrderEntityWithItems(
        SpyCustomer $customer,
        SpySalesOrderAddress $billingAddress,
        SpySalesOrderAddress $shippingAddress
    ): SpySalesOrder {
        $orderEntity = (new SpySalesOrder())
            ->setEmail($customer->getEmail())
            ->setIsTest(true)
            ->setFkSalesOrderAddressBilling($billingAddress->getIdSalesOrderAddress())
            ->setFkSalesOrderAddressShipping($shippingAddress->getIdSalesOrderAddress())
            ->setCustomer($customer)
            ->setOrderReference('reference-' . $customer->getEmail());
        $orderEntity->save();
        $orderItemEntity = $this->createOrderItemEntity($orderEntity->getIdSalesOrder());

        $this->createTotalsEntity($orderEntity, $orderItemEntity);

        return $orderEntity;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    private function createOrderItemEntity(int $idSalesOrder): SpySalesOrderItem
    {
        $stateEntity = $this->createOrderItemStateEntity();
        $processEntity = $this->createOrderProcessEntity();

        $orderItemEntity = new SpySalesOrderItem();
        $orderItemEntity
            ->setFkSalesOrder($idSalesOrder)
            ->setFkOmsOrderItemState($stateEntity->getIdOmsOrderItemState())
            ->setFkOmsOrderProcess($processEntity->getIdOmsOrderProcess())
            ->setName('test product')
            ->setSku('1324354657687980')
            ->setGrossPrice(1000)
            ->setPrice(1000)
            ->setNetPrice(1000)
            ->setPriceToPayAggregation(1000)
            ->setRefundableAmount(1000)
            ->setQuantity(1);
        $orderItemEntity->save();

        return $orderItemEntity;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    private function createOrderItemStateEntity(): SpyOmsOrderItemState
    {
        $stateEntity = new SpyOmsOrderItemState();
        $stateEntity->setName($this->getUniqueOrderItemState());
        $stateEntity->save();

        return $stateEntity;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    private function createOrderProcessEntity(): SpyOmsOrderProcess
    {
        $processEntity = new SpyOmsOrderProcess();
        $processEntity->setName($this->getUniqueOmsProcess());
        $processEntity->save();

        return $processEntity;
    }

    /**
     * @return string
     */
    public function getUniqueOrderItemState(): string
    {
        if ($this->uniqueOrderItemState === null) {
            $this->uniqueOrderItemState = uniqid() . '-state';
        }

        return $this->uniqueOrderItemState;
    }

    /**
     * @return string
     */
    public function getUniqueOmsProcess(): string
    {
        if ($this->uniqueOmsProcess === null) {
            $this->uniqueOmsProcess = uniqid() . '-process';
        }

        return $this->uniqueOmsProcess;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     *
     * @return void
     */
    protected function createTotalsEntity(SpySalesOrder $orderEntity, SpySalesOrderItem $orderItemEntity): void
    {
        $totals = new SpySalesOrderTotals();
        $totals->setFkSalesOrder($orderEntity->getIdSalesOrder());
        $totals->setGrandTotal($orderItemEntity->getGrossPrice());
        $totals->save();
    }
}
