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
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeOnRegistrationInterface;

class AuthorizeOnRegistrationTransaction implements AuthorizeOnRegistrationTransactionInterface
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
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeOnRegistrationRequestTransfer
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeOnRegistrationInterface $paymentAdapter
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function executeTransaction(
        HeidelpayRequestTransfer $authorizeOnRegistrationRequestTransfer,
        PaymentWithAuthorizeOnRegistrationInterface $paymentAdapter
    ) {
        $authorizeOnRegistrationResponseTransfer = $paymentAdapter->authorizeOnRegistration($authorizeOnRegistrationRequestTransfer);
        $this->logTransaction($authorizeOnRegistrationRequestTransfer, $authorizeOnRegistrationResponseTransfer);

        return $authorizeOnRegistrationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeOnRegistrationRequestTransfer
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $authorizeOnRegistrationResponseTransfer
     *
     * @return void
     */
    protected function logTransaction(
        HeidelpayRequestTransfer $authorizeOnRegistrationRequestTransfer,
        HeidelpayResponseTransfer $authorizeOnRegistrationResponseTransfer
    ) {
        $this->transactionLogger->logTransaction(
            HeidelpayConfig::TRANSACTION_TYPE_AUTHORIZE_ON_REGISTRATION,
            $authorizeOnRegistrationRequestTransfer,
            $authorizeOnRegistrationResponseTransfer,
        );
    }
}
