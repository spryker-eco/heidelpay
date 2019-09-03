<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Processor\Notification;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Expander\NotificationExpanderInterface;
use SprykerEco\Zed\Heidelpay\Business\Writer\HeidelpayWriterInterface;

class HeidelpayNotificationProcessor implements HeidelpayNotificationProcessorInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Expander\NotificationExpanderInterface
     */
    protected $notificationExpander;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Writer\HeidelpayWriterInterface
     */
    protected $writer;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Expander\NotificationExpanderInterface $notificationExpander
     * @param \SprykerEco\Zed\Heidelpay\Business\Writer\HeidelpayWriterInterface $writer
     */
    public function __construct(
        NotificationExpanderInterface $notificationExpander,
        HeidelpayWriterInterface $writer
    ) {
        $this->notificationExpander = $notificationExpander;
        $this->writer = $writer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function processNotification(HeidelpayNotificationTransfer $notificationTransfer): HeidelpayNotificationTransfer
    {
        $notificationTransfer = $this->notificationExpander->expandWithNotificationData($notificationTransfer);
        $this->writer->createNotificationEntity($notificationTransfer);

        return $notificationTransfer;
    }
}
