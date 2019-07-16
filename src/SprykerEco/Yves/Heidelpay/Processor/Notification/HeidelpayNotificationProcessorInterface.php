<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Processor\Notification;

use Symfony\Component\HttpFoundation\Request;

interface HeidelpayNotificationProcessorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function processNotification(Request $request): void;
}
