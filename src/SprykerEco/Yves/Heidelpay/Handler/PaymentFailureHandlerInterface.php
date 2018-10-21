<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Handler;

use Generated\Shared\Transfer\HeidelpayErrorRedirectResponseTransfer;

interface PaymentFailureHandlerInterface
{
    /**
     * @param string $errorCode
     *
     * @return \Generated\Shared\Transfer\HeidelpayErrorRedirectResponseTransfer
     */
    public function handlePaymentFailureByErrorCode($errorCode): HeidelpayErrorRedirectResponseTransfer;
}
