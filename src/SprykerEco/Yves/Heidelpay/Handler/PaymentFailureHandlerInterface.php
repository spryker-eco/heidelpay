<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
