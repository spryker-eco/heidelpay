<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction;

use Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface;

interface ExternalResponseTransactionInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface $paymentAdapter
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function executeTransaction(
        HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer,
        PaymentWithExternalResponseInterface $paymentAdapter
    ): HeidelpayResponseTransfer;
}
