<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders;

use Orm\Zed\Sales\Persistence\SpySalesOrder;

class OrderWithUnsuccessfulIdealAuthorizeTransaction extends OrderWithSuccessfulIdealAuthorizeTransaction
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    protected function createTransaction(SpySalesOrder $orderEntity): void
    {
        $this->createUnsuccessfulAuthorizeTransactionForOrder($orderEntity);
    }
}
