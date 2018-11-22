<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;

interface ExternalResponseTransactionHandlerInterface
{
    /**
     * @param array $responseArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalPaymentResponse(array $responseArray): HeidelpayPaymentProcessingResponseTransfer;
}
