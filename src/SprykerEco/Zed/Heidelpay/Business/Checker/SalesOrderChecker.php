<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Checker;

use Generated\Shared\Transfer\PaymentHeidelpayTransactionLogCriteriaTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface;

class SalesOrderChecker implements SalesOrderCheckerInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface
     */
    protected $heidelpayRepository;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface $heidelpayRepository
     */
    public function __construct(HeidelpayRepositoryInterface $heidelpayRepository)
    {
        $this->heidelpayRepository = $heidelpayRepository;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function isCaptureApproved(int $idSalesOrder): bool
    {
        $paymentHeidelpayTransactionLogCriteriaTransfer = (new PaymentHeidelpayTransactionLogCriteriaTransfer())
            ->setIdSalesOrder($idSalesOrder)
            ->setTransactionType(HeidelpayConfig::TRANSACTION_TYPE_CAPTURE)
            ->setResponseCode(HeidelpayConfig::CAPTURE_TRANSACTION_STATUS_OK);

        return $this->heidelpayRepository->hasPaymentHeidelpayTransactionLog($paymentHeidelpayTransactionLogCriteriaTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function isRefunded(int $idSalesOrder): bool
    {
        $paymentHeidelpayTransactionLogCriteriaTransfer = (new PaymentHeidelpayTransactionLogCriteriaTransfer())
            ->setIdSalesOrder($idSalesOrder)
            ->setTransactionType(HeidelpayConfig::TRANSACTION_TYPE_REFUND)
            ->setResponseCode(HeidelpayConfig::REFUND_TRANSACTION_STATUS_OK);

        return $this->heidelpayRepository->hasPaymentHeidelpayTransactionLog($paymentHeidelpayTransactionLogCriteriaTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function isDebitOnRegistrationCompleted(int $idSalesOrder): bool
    {
        $paymentHeidelpayTransactionLogCriteriaTransfer = (new PaymentHeidelpayTransactionLogCriteriaTransfer())
            ->setIdSalesOrder($idSalesOrder)
            ->setTransactionType(HeidelpayConfig::TRANSACTION_TYPE_EXTERNAL_RESPONSE)
            ->setResponseCode(HeidelpayConfig::EXTERNAL_RESPONSE_TRANSACTION_STATUS_OK);

        return $this->heidelpayRepository->hasPaymentHeidelpayTransactionLog($paymentHeidelpayTransactionLogCriteriaTransfer);
    }
}
