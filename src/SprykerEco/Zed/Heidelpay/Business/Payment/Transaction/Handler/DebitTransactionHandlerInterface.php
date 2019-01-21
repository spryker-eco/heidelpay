<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\OrderTransfer;

interface DebitTransactionHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function debit(OrderTransfer $orderTransfer): void;
}
