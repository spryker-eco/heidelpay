<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
