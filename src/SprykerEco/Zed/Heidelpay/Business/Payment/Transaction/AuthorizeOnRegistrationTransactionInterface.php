<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeOnRegistrationInterface;

interface AuthorizeOnRegistrationTransactionInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeOnRegistrationRequestTransfer
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeOnRegistrationInterface $paymentAdapter
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function executeTransaction(
        HeidelpayRequestTransfer $authorizeOnRegistrationRequestTransfer,
        PaymentWithAuthorizeOnRegistrationInterface $paymentAdapter
    );
}
