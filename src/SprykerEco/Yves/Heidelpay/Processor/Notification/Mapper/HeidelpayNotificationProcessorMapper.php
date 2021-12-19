<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Processor\Notification\Mapper;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Symfony\Component\HttpFoundation\Request;

class HeidelpayNotificationProcessorMapper implements HeidelpayNotificationProcessorMapperInterface
{
    /**
     * @var string
     */
    protected const HEADER_X_PUSH_TIMESTAMP = 'X-Push-Timestamp';

    /**
     * @var string
     */
    protected const HEADER_X_PUSH_RETRIES = 'X-Push-Retries';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function mapRequestToNotificationTransfer(
        Request $request,
        HeidelpayNotificationTransfer $notificationTransfer
    ): HeidelpayNotificationTransfer {
        $notificationTransfer
            ->setNotificationBody($request->getContent())
            ->setTimestamp($request->headers->get(static::HEADER_X_PUSH_TIMESTAMP))
            ->setRetries((int)$request->headers->get(static::HEADER_X_PUSH_RETRIES));

        return $notificationTransfer;
    }
}
