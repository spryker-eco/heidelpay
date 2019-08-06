<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PaymentHeidelpayDirectDebitRegistrationTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistration;

class HeidelpayPersistenceMapper
{
    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistration $paymentHeidelpayDirectDebitRegistrationEntity
     * @param \Generated\Shared\Transfer\PaymentHeidelpayDirectDebitRegistrationTransfer $heidelpayDirectDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentHeidelpayDirectDebitRegistrationTransfer
     */
    public function mapEntityToHeidelpayDirectDebitRegistrationTransfer(
        SpyPaymentHeidelpayDirectDebitRegistration $paymentHeidelpayDirectDebitRegistrationEntity,
        PaymentHeidelpayDirectDebitRegistrationTransfer $heidelpayDirectDebitRegistrationTransfer
    ): PaymentHeidelpayDirectDebitRegistrationTransfer {
        $heidelpayDirectDebitRegistrationTransfer->fromArray(
            $paymentHeidelpayDirectDebitRegistrationEntity->toArray(),
            true
        )->setIdCustomerAddress($paymentHeidelpayDirectDebitRegistrationEntity->getFkCustomerAddress());

        return $heidelpayDirectDebitRegistrationTransfer;
    }
}
