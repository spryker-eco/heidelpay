<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Checker;

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
        $heidelpayTransactionLogTransfer = $this->heidelpayRepository
            ->findHeidelpayTransactionLogByIdSalesOrderAndTransactionTypeAndResponseCode(
                $idSalesOrder,
                HeidelpayConfig::TRANSACTION_TYPE_CAPTURE,
                HeidelpayConfig::CAPTURE_TRANSACTION_STATUS_OK
            );

        if (!$heidelpayTransactionLogTransfer) {
            return false;
        }

        return true;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function isRefunded(int $idSalesOrder): bool
    {
        $heidelpayTransactionLogTransfer = $this->heidelpayRepository
            ->findHeidelpayTransactionLogByIdSalesOrderAndTransactionTypeAndResponseCode(
                $idSalesOrder,
                HeidelpayConfig::TRANSACTION_TYPE_REFUND,
                HeidelpayConfig::REFUND_TRANSACTION_STATUS_OK
            );

        if (!$heidelpayTransactionLogTransfer) {
            return false;
        }

        return true;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function isDebitOnRegistrationCompleted(int $idSalesOrder): bool
    {
        $heidelpayTransactionLogTransfer = $this->heidelpayRepository
            ->findHeidelpayTransactionLogByIdSalesOrderAndTransactionTypeAndResponseCode(
                $idSalesOrder,
                HeidelpayConfig::TRANSACTION_TYPE_EXTERNAL_RESPONSE,
                HeidelpayConfig::EXTERNAL_RESPONSE_TRANSACTION_STATUS_OK
            );

        if (!$heidelpayTransactionLogTransfer) {
            return false;
        }

        return true;
    }
}
