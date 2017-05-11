<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heidelpay\Business\Payment\Transaction;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface;

interface CaptureTransactionInterface
{

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $captureRequestTransfer
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface $paymentAdapter
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function executeTransaction(
        HeidelpayRequestTransfer $captureRequestTransfer,
        PaymentWithCaptureInterface $paymentAdapter
    );

}
