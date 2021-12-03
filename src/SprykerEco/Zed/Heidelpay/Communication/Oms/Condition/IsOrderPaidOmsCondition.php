<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Communication\Oms\Condition;

use Generated\Shared\Transfer\HeidelpayNotificationCollectionTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeInterface;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface;

class IsOrderPaidOmsCondition implements HeidelpayOmsConditionInterface
{
    /**
     * @var string
     */
    protected const PAID_RECEIPT_PAYMENT_CODE = 'IV.RC';

    /**
     * @var \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface
     */
    protected $repository;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface $repository
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        HeidelpayRepositoryInterface $repository,
        HeidelpayToSalesFacadeInterface $salesFacade
    ) {
        $this->repository = $repository;
        $this->salesFacade = $salesFacade;
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
                static::PAID_RECEIPT_PAYMENT_CODE
            );

        if ($heidelpayNotificationCollection->getNotifications()->count() === 0) {
            return false;
        }

        $orderTransfer = $this->salesFacade
            ->getOrderByIdSalesOrder($orderItem->getFkSalesOrder());

        return $this->isOrderPaid($heidelpayNotificationCollection, $orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationCollectionTransfer $heidelpayNotificationCollection
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function isOrderPaid(
        HeidelpayNotificationCollectionTransfer $heidelpayNotificationCollection,
        OrderTransfer $orderTransfer
    ): bool {
        $paidAmount = 0;

        foreach ($heidelpayNotificationCollection->getNotifications() as $heidelpayNotificationTransfer) {
            $paidAmount += $heidelpayNotificationTransfer->getAmount();
        }

        return $paidAmount === $orderTransfer->getTotals()->getPriceToPay();
    }
}
