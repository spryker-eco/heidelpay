<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Communication\Oms\Condition;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface;

class IsFinalizingFailedOmsCondition implements HeidelpayOmsConditionInterface
{
    /**
     * @var string
     */
    protected const FINALIZE_PAYMENT_CODE = 'IV.FI';

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
        $heidelpayNotificationCollection = $this->repository
            ->getPaymentHeidelpayNotificationCollectionByTransactionIdAndPaymentCode(
                (string)$orderItem->getFkSalesOrder(),
                static::FINALIZE_PAYMENT_CODE
            );

        if ($heidelpayNotificationCollection->getNotifications()->count() === 0) {
            return false;
        }

        return $this->isFinalizingFailed(
            $heidelpayNotificationCollection->getNotifications()->offsetGet(0)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $heidelpayNotificationTransfer
     *
     * @return bool
     */
    protected function isFinalizingFailed(HeidelpayNotificationTransfer $heidelpayNotificationTransfer): bool
    {
        return $heidelpayNotificationTransfer->getResult() === HeidelpayConfig::NOTIFICATION_STATUS_FAILED;
    }
}
