<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Order;

use SprykerEco\Zed\Heidelpay\Dependency\QueryContainer\HeidelpayToSalesQueryContainerInterface;

class OrderReader implements OrderReaderInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\QueryContainer\HeidelpayToSalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Dependency\QueryContainer\HeidelpayToSalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(HeidelpayToSalesQueryContainerInterface $salesQueryContainer)
    {
        $this->salesQueryContainer = $salesQueryContainer;
    }

    /**
     * @param string $orderReference
     *
     * @return int
     */
    public function getOrderIdByReference(string $orderReference): int
    {
        $orderEntity = $this->salesQueryContainer->getOrderByReference($orderReference);

        return $orderEntity->getIdSalesOrder();
    }
}
