<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotification;

class HeidelpayPersistenceMapper
{
    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotification $paymentHeidelpayNotification
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $heidelpayNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function mapEntityToHeidelpayNotificationTransfer(
        SpyPaymentHeidelpayNotification $paymentHeidelpayNotification,
        HeidelpayNotificationTransfer $heidelpayNotificationTransfer
    ): HeidelpayNotificationTransfer {
        $heidelpayNotificationTransfer->fromArray(
            $paymentHeidelpayNotification->toArray(),
            true
        );

        return $heidelpayNotificationTransfer;
    }
}
