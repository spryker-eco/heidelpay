<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\HeidelpayDirectDebitAccountTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayNotificationCollectionTransfer;
use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistration;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotification;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog;
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
            true,
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
                new HeidelpayNotificationTransfer(),
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
            true,
        );

        return $heidelpayNotificationTransfer;
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistration $paymentHeidelpayDirectDebitRegistrationEntity
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function mapEntityToHeidelpayDirectDebitRegistrationTransfer(
        SpyPaymentHeidelpayDirectDebitRegistration $paymentHeidelpayDirectDebitRegistrationEntity,
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        $directDebitRegistrationTransfer
            ->setIdDirectDebitRegistration($paymentHeidelpayDirectDebitRegistrationEntity->getIdDirectDebitRegistration())
            ->setIdCustomerAddress($paymentHeidelpayDirectDebitRegistrationEntity->getFkCustomerAddress())
            ->setRegistrationUniqueId($paymentHeidelpayDirectDebitRegistrationEntity->getRegistrationUniqueId())
            ->setTransactionId($paymentHeidelpayDirectDebitRegistrationEntity->getTransactionId())
            ->setAccountInfo(
                (new HeidelpayDirectDebitAccountTransfer())
                    ->fromArray(
                        $paymentHeidelpayDirectDebitRegistrationEntity->toArray(),
                        true,
                    ),
            );

        return $directDebitRegistrationTransfer;
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog $paymentHeidelpayTransactionLogEntity
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $heidelpayTransactionLogTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    public function mapEntityToHeidelpayTransactionLogTransfer(
        SpyPaymentHeidelpayTransactionLog $paymentHeidelpayTransactionLogEntity,
        HeidelpayTransactionLogTransfer $heidelpayTransactionLogTransfer
    ): HeidelpayTransactionLogTransfer {
        $heidelpayTransactionLogTransfer->fromArray(
            $paymentHeidelpayTransactionLogEntity->toArray(),
            true,
        )->setIdSalesOrder($paymentHeidelpayTransactionLogEntity->getFkSalesOrder());

        return $heidelpayTransactionLogTransfer;
    }
}
