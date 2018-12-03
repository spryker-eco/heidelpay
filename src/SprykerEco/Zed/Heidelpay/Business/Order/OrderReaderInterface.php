<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Order;

interface OrderReaderInterface
{
    /**
     * @param string $orderReference
     *
     * @return int
     */
    public function getOrderIdByReference(string $orderReference): int;
}
