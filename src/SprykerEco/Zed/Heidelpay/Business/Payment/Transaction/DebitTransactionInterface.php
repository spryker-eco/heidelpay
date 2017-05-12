<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface;

interface DebitTransactionInterface
{

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $debitRequestTransfer
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface $paymentAdapter
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function executeTransaction(
        HeidelpayRequestTransfer $debitRequestTransfer,
        PaymentWithDebitInterface $paymentAdapter
    );

}
