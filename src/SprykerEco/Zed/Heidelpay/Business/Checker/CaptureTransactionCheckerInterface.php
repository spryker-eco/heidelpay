<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Checker;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

interface CaptureTransactionCheckerInterface
{
    /**
     * @return bool
     */
    public function isSuccessful(SpySalesOrderItem $orderItem): bool;
}
