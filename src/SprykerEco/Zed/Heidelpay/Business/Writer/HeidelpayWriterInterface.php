<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Writer;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;

interface HeidelpayWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     *
     * @return void
     */
    public function createNotificationEntity(HeidelpayNotificationTransfer $notificationTransfer): void;
}
