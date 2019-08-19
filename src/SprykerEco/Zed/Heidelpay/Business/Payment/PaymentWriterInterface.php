<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment;

use Generated\Shared\Transfer\HeidelpayResponseTransfer;

interface PaymentWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function updateHeidelpayPaymentWithResponse(HeidelpayResponseTransfer $responseTransfer): void;
}
