<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;

interface TransactionLoggerInterface
{
    /**
     * @param string $transactionType
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $transactionRequest
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $transactionResponse
     *
     * @return void
     */
    public function logTransaction(
        string $transactionType,
        HeidelpayRequestTransfer $transactionRequest,
        HeidelpayResponseTransfer $transactionResponse
    ): void;
}
