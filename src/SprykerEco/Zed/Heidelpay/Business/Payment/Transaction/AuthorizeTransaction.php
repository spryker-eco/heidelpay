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
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface;

class AuthorizeTransaction implements AuthorizeTransactionInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface
     */
    protected $transactionLogger;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     */
    public function __construct(TransactionLoggerInterface $transactionLogger)
    {
        $this->transactionLogger = $transactionLogger;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface $paymentAdapter
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function executeTransaction(
        HeidelpayRequestTransfer $authorizeRequestTransfer,
        PaymentWithAuthorizeInterface $paymentAdapter
    ): HeidelpayResponseTransfer {
        $authorizeResponseTransfer = $paymentAdapter->authorize($authorizeRequestTransfer);
        $this->logTransaction($authorizeRequestTransfer, $authorizeResponseTransfer);

        return $authorizeResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $authorizeResponseTransfer
     *
     * @return void
     */
    protected function logTransaction(
        HeidelpayRequestTransfer $authorizeRequestTransfer,
        HeidelpayResponseTransfer $authorizeResponseTransfer
    ): void {
        $this->transactionLogger->logTransaction(
            HeidelpayConfig::TRANSACTION_TYPE_AUTHORIZE,
            $authorizeRequestTransfer,
            $authorizeResponseTransfer
        );
    }
}
