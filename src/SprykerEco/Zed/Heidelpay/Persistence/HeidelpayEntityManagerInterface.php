<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

use Generated\Shared\Transfer\PaymentHeidelpayDirectDebitRegistrationTransfer;

interface HeidelpayEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentHeidelpayDirectDebitRegistrationTransfer $paymentHeidelpayDirectDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentHeidelpayDirectDebitRegistrationTransfer
     */
    public function savePaymentHeidelpayDirectDebitRegistrationEntity(
        PaymentHeidelpayDirectDebitRegistrationTransfer $paymentHeidelpayDirectDebitRegistrationTransfer
    ): PaymentHeidelpayDirectDebitRegistrationTransfer;
}
