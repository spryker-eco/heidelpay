<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\OrderTransfer;

interface FinalizeTransactionHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $quoteTransfer
     *
     * @return void
     */
    public function finalize(OrderTransfer $orderTransfer);
}
