<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
