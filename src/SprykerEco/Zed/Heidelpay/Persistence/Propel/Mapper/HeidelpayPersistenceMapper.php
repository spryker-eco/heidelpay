<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\HeidelpayDirectDebitAccountTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistration;

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
}
