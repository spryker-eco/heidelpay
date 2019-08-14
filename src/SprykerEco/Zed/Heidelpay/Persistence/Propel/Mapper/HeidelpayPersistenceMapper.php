<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\HeidelpayDirectDebitAccountTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistration;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog;

class HeidelpayPersistenceMapper
{
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
                        true
                    )
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
            true
        )->setIdSalesOrder($paymentHeidelpayTransactionLogEntity->getFkSalesOrder());

        return $heidelpayTransactionLogTransfer;
    }
}
