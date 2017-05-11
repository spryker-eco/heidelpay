<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heidelpay\Business\Payment\Transaction\Logger;

use Generated\Shared\Transfer\HeidelpayResponseTransfer;

interface TransactionLoggerInterface
{

    /**
     * @param string $transactionType
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer|null $transactionRequest
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $transactionResponse
     *
     * @return void
     */
    public function logTransaction(
        $transactionType,
        $transactionRequest,
        HeidelpayResponseTransfer $transactionResponse
    );

}
