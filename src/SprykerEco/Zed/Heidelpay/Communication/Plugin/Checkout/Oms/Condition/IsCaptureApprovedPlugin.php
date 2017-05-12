<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Communication\Plugin\Checkout\Oms\Condition;

use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Shared\Heidelpay\HeidelpayConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \Spryker\Zed\Heidelpay\Business\HeidelpayFacade getFacade()
 * @method \Spryker\Zed\Heidelpay\Persistence\HeidelpayQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Heidelpay\Communication\HeidelpayCommunicationFactory getFactory()
 */
class IsCaptureApprovedPlugin extends AbstractPlugin implements ConditionInterface
{

    const CAPTURE_TRANSACTION_STATUS_OK = HeidelpayConstants::CAPTURE_TRANSACTION_STATUS_OK;

    /**
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        return $this->isCaptureSuccessful($orderItem->getFkSalesOrder());
    }

    /**
     * @param int $idSalesOrder
     *
     * @return bool
     */
    protected function isCaptureSuccessful($idSalesOrder)
    {
        $captureTransactionLog = $this->getCaptureTransactionLogEntry($idSalesOrder);
        if ($captureTransactionLog === null) {
            return false;
        }

        return $this->isTransactionSuccessful($captureTransactionLog);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog|null
     */
    protected function getCaptureTransactionLogEntry($idSalesOrder)
    {
        $transactionLogQuery = $this->getQueryContainer()->queryCaptureTransactionLog($idSalesOrder);
        return $transactionLogQuery->findOne();
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog $caprureTransactionLog
     *
     * @return bool
     */
    protected function isTransactionSuccessful(SpyPaymentHeidelpayTransactionLog $caprureTransactionLog)
    {
        return $caprureTransactionLog->getResponseCode() === static::CAPTURE_TRANSACTION_STATUS_OK;
    }

}
