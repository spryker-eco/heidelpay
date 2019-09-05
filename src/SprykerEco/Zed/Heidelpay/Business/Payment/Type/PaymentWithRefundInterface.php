<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Type;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;

interface PaymentWithRefundInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $refundRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function refund(HeidelpayRequestTransfer $refundRequestTransfer);
}
