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
class IsAuthorizationCompletedPlugin extends AbstractPlugin implements ConditionInterface
{

    const EXTERNAL_RESPONSE_TRANSACTION_STATUS_OK = HeidelpayConstants::EXTERNAL_RESPONSE_TRANSACTION_STATUS_OK;

    /**
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        return $this->hasCustomerCompletedAuthorization($orderItem->getFkSalesOrder());
    }

    /**
     * @param int $idSalesOrder
     *
     * @return bool
     */
    protected function hasCustomerCompletedAuthorization($idSalesOrder)
    {
        $externalTransactionLog = $this->getExternalTransactionLogEntry($idSalesOrder);
        if ($externalTransactionLog === null) {
            return false;
        }

        return $this->isTransactionSuccessful($externalTransactionLog);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog|null
     */
    protected function getExternalTransactionLogEntry($idSalesOrder)
    {
        $transactionLogQuery = $this->getQueryContainer()->queryExternalResponseTransactionLog($idSalesOrder);
        return $transactionLogQuery->findOne();
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog $externalTransactionLog
     *
     * @return bool
     */
    protected function isTransactionSuccessful(SpyPaymentHeidelpayTransactionLog $externalTransactionLog)
    {
        return $externalTransactionLog->getResponseCode() === static::EXTERNAL_RESPONSE_TRANSACTION_STATUS_OK;
    }

}
