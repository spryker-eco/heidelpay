<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Processor\Notification;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Expander\NotificationExpanderInterface;

class HeidelpayNotificationProcessor implements HeidelpayNotificationProcessorInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Expander\NotificationExpanderInterface
     */
    protected $notificationExpander;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Expander\NotificationExpanderInterface $notificationExpander
     */
    public function __construct(NotificationExpanderInterface $notificationExpander)
    {
        $this->notificationExpander = $notificationExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function processNotification(HeidelpayNotificationTransfer $notificationTransfer): HeidelpayNotificationTransfer
    {
        $notificationTransfer = $this->notificationExpander->expandWithNotificationData($notificationTransfer);

        return $notificationTransfer;
    }
}
