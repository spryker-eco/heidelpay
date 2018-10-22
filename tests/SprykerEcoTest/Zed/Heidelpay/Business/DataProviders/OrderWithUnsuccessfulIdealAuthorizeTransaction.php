<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
