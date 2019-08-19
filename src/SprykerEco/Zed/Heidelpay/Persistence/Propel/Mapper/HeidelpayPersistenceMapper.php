<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotification;

class HeidelpayPersistenceMapper
{
    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay $paymentHeidelpayEntity
     * @param \Generated\Shared\Transfer\HeidelpayPaymentTransfer $heidelpayPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentTransfer
     */
    public function mapEntityToHeidelpayPaymentTransfer(
        SpyPaymentHeidelpay $paymentHeidelpayEntity,
        HeidelpayPaymentTransfer $heidelpayPaymentTransfer
    ): HeidelpayPaymentTransfer {
        $heidelpayPaymentTransfer->fromArray(
            $paymentHeidelpayEntity->toArray(),
            true
        );

        return $heidelpayPaymentTransfer;
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotification $paymentHeidelpayNotificationEntity
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $heidelpayNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function mapEntityToHeidelpayNotificationTransfer(
        SpyPaymentHeidelpayNotification $paymentHeidelpayNotificationEntity,
        HeidelpayNotificationTransfer $heidelpayNotificationTransfer
    ): HeidelpayNotificationTransfer {
        $heidelpayNotificationTransfer->fromArray(
            $paymentHeidelpayNotificationEntity->toArray(),
            true
        );

        return $heidelpayNotificationTransfer;
    }
}
