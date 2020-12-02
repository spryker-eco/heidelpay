<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Checker;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface;

class CaptureTransactionChecker implements CaptureTransactionCheckerInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface
     */
    protected $heidelpayQueryContainer;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface $heidelpayQueryContainer
     */
    public function __construct(HeidelpayQueryContainerInterface $heidelpayQueryContainer)
    {
        $this->heidelpayQueryContainer = $heidelpayQueryContainer;
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccessful(SpySalesOrderItem $orderItem): bool
    {
        $successTransactionLogEntity = $this->heidelpayQueryContainer
            ->queryCaptureSuccessTransactionLog($orderItem->getFkSalesOrder())
            ->findOne();

        return ($successTransactionLogEntity !== null);
    }
}
