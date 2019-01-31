<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithInitializeInterface;

class InitializeTransaction implements InitializeTransactionInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $initializeRequestTransfer
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithInitializeInterface $paymentAdapter
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function executeTransaction(
        HeidelpayRequestTransfer $initializeRequestTransfer,
        PaymentWithInitializeInterface $paymentAdapter
    ) {
        $initializeResponseTransfer = $paymentAdapter->initialize($initializeRequestTransfer);

        return $initializeResponseTransfer;
    }
}
