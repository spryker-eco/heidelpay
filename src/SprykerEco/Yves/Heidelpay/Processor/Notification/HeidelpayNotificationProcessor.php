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
    protected $heidelpayClient;

    /**
     * @param \SprykerEco\Yves\Heidelpay\Processor\Notification\Mapper\HeidelpayNotificationProcessorMapperInterface $mapper
     * @param \SprykerEco\Client\Heidelpay\HeidelpayClientInterface $heidelpayClient
     */
    public function __construct(
        HeidelpayNotificationProcessorMapperInterface $mapper,
        HeidelpayClientInterface $heidelpayClient
    ) {
        $this->mapper = $mapper;
        $this->heidelpayClient = $heidelpayClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function processNotification(Request $request): void
    {
        $notificationTransfer = $this->mapper
            ->mapRequestToNotificationTransfer(
                $request,
                new HeidelpayNotificationTransfer()
            );

        $this->heidelpayClient->processNotification($notificationTransfer);
    }
}
