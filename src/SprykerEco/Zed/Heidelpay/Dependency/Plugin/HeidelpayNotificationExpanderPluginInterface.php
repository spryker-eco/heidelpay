<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Dependency\Plugin;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;

interface HeidelpayNotificationExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `HeidelpayNotificationTransfer` with data from request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     * @param string[][] $notificationData
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function expand(HeidelpayNotificationTransfer $notificationTransfer, array $notificationData): HeidelpayNotificationTransfer;
}
