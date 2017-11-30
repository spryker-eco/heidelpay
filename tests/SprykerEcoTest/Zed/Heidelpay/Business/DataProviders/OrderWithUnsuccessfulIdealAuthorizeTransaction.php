<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders;

class OrderWithUnsuccessfulIdealAuthorizeTransaction extends OrderWithSuccessfulIdealAuthorizeTransaction
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    protected function createTransaction($orderEntity)
    {
        $this->createUnsuccessfulAuthorizeTransactionForOrder($orderEntity);
    }

}
