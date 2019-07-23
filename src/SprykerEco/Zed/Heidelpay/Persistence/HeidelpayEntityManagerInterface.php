<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;

interface HeidelpayEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $heidelpayNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function savePaymentHeidelpayNotificationEntity(
        HeidelpayNotificationTransfer $heidelpayNotificationTransfer
    ): HeidelpayNotificationTransfer;
}
