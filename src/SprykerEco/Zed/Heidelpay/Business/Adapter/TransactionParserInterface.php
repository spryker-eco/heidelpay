<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter;

use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;

interface TransactionParserInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $transactionLogTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function getHeidelpayResponseTransfer(HeidelpayTransactionLogTransfer $transactionLogTransfer): HeidelpayResponseTransfer;
}
