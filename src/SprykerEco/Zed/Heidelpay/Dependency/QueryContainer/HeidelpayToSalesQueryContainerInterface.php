<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Dependency\QueryContainer;

use Orm\Zed\Sales\Persistence\SpySalesOrder;

interface HeidelpayToSalesQueryContainerInterface
{
    /**
     * @param string $orderReference
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function getOrderByReference(string $orderReference): SpySalesOrder;
}
