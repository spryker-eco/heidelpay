<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter;

use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;

interface TransactionParserInterface
{

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $transactionLogTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function getHeidelpayResponseTransfer(HeidelpayTransactionLogTransfer $transactionLogTransfer);

}
