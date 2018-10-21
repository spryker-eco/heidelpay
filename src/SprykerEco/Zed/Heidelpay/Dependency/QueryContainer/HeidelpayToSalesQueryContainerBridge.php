<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Dependency\QueryContainer;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class HeidelpayToSalesQueryContainerBridge implements HeidelpayToSalesQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(SalesQueryContainerInterface $salesQueryContainer)
    {
        $this->salesQueryContainer = $salesQueryContainer;
    }

    /**
     * @param string $orderReference
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function getOrderByReference(string $orderReference): SpySalesOrder
    {
        return $this->salesQueryContainer->querySalesOrder()
            ->findOneByOrderReference($orderReference);
    }
}
