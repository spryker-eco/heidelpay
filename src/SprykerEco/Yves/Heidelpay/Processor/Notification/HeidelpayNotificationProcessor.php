<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Processor\Notification;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use SprykerEco\Client\Heidelpay\HeidelpayClientInterface;
use SprykerEco\Yves\Heidelpay\Processor\Notification\Mapper\HeidelpayNotificationProcessorMapperInterface;
use Symfony\Component\HttpFoundation\Request;

class HeidelpayNotificationProcessor implements HeidelpayNotificationProcessorInterface
{
    /**
     * @var \SprykerEco\Yves\Heidelpay\Processor\Notification\Mapper\HeidelpayNotificationProcessorMapperInterface
     */
    protected $mapper;

    /**
     * @var \SprykerEco\Client\Heidelpay\HeidelpayClientInterface
     */
    protected $client;

    /**
     * @param \SprykerEco\Yves\Heidelpay\Processor\Notification\Mapper\HeidelpayNotificationProcessorMapperInterface $mapper
     * @param \SprykerEco\Client\Heidelpay\HeidelpayClientInterface $client
     */
    public function __construct(
        HeidelpayNotificationProcessorMapperInterface $mapper,
        HeidelpayClientInterface $client
    ) {
        $this->mapper = $mapper;
        $this->client = $client;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function processNotification(Request $request): HeidelpayNotificationTransfer
    {
        $notificationTransfer = $this->mapper
            ->mapRequestToNotificationTransfer(
                $request,
                new HeidelpayNotificationTransfer()
            );

        return $this->client->processNotification($notificationTransfer);
    }
}
