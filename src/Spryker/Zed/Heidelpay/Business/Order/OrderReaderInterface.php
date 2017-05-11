<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heidelpay\Business\Order;

interface OrderReaderInterface
{

    /**
     * @param string $orderReference
     *
     * @return int
     */
    public function getOrderIdByReference($orderReference);

}
