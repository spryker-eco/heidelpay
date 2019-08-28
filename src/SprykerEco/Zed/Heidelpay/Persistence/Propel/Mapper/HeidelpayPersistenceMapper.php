<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\HeidelpayNotificationCollectionTransfer;
use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotification;
use Propel\Runtime\Collection\ObjectCollection;

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
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotification[] $paymentHeidelpayNotificationEntities
     * @param \Generated\Shared\Transfer\HeidelpayNotificationCollectionTransfer $heidelpayNotificationCollection
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationCollectionTransfer
     */
    public function mapNotificationEntitiesToHeidelpayNotificationCollection(
        ObjectCollection $paymentHeidelpayNotificationEntities,
        HeidelpayNotificationCollectionTransfer $heidelpayNotificationCollection
    ): HeidelpayNotificationCollectionTransfer {
        foreach ($paymentHeidelpayNotificationEntities as $paymentHeidelpayNotificationEntity) {
            $heidelpayNotificationTransfer = $this->mapEntityToHeidelpayNotificationTransfer(
                $paymentHeidelpayNotificationEntity,
                new HeidelpayNotificationTransfer()
            );
            $heidelpayNotificationCollection->addNotification($heidelpayNotificationTransfer);
        }

        return $heidelpayNotificationCollection;
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
