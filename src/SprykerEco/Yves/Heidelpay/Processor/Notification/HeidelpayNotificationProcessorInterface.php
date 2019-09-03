<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Processor\Notification;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Symfony\Component\HttpFoundation\Request;

interface HeidelpayNotificationProcessorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function processNotification(Request $request): HeidelpayNotificationTransfer;
}
