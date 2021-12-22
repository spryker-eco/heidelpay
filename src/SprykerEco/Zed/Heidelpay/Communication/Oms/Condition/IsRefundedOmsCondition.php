<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Communication\Oms\Condition;

use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface;

class IsRefundedOmsCondition implements HeidelpayOmsConditionInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface
     */
    protected $repository;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface $repository
     */
    public function __construct(HeidelpayRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        $externalTransactionLog = $this->repository
            ->findHeidelpayTransactionLogByIdSalesOrderAndTransactionType(
                $orderItem->getFkSalesOrder(),
                HeidelpayConfig::TRANSACTION_TYPE_REFUND,
            );

        if ($externalTransactionLog === null) {
            return false;
        }

        return $this->isTransactionSuccessful($externalTransactionLog);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $externalTransactionLog
     *
     * @return bool
     */
    protected function isTransactionSuccessful(HeidelpayTransactionLogTransfer $externalTransactionLog): bool
    {
        return $externalTransactionLog->getResponseCode() === HeidelpayConfig::REFUND_TRANSACTION_STATUS_OK;
    }
}
