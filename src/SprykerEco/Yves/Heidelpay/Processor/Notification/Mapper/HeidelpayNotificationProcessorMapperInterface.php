<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Processor\Notification\Mapper;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Symfony\Component\HttpFoundation\Request;

interface HeidelpayNotificationProcessorMapperInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function mapRequestToNotificationTransfer(
        Request $request,
        HeidelpayNotificationTransfer $notificationTransfer
    ): HeidelpayNotificationTransfer;
}
