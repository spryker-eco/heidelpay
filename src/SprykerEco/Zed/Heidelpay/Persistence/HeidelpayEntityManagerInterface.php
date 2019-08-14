<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;

interface HeidelpayEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function savePaymentHeidelpayDirectDebitRegistrationEntity(
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer;
}
