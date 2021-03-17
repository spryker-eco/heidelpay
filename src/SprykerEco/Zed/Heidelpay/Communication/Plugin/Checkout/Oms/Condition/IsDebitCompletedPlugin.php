<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Communication\Plugin\Checkout\Oms\Condition;

use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;

/**
 * @method \SprykerEco\Zed\Heidelpay\Business\HeidelpayFacadeInterface getFacade()
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\Heidelpay\Communication\HeidelpayCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\Heidelpay\HeidelpayConfig getConfig()
 */
class IsDebitCompletedPlugin extends AbstractPlugin implements ConditionInterface
{
    /**
     * {@inheritDoc}
     * - Checks if Debit transaction was successful.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        return $this->hasCustomerCompletedDebit($orderItem->getFkSalesOrder());
    }

    /**
     * @param int $idSalesOrder
     *
     * @return bool
     */
    protected function hasCustomerCompletedDebit(int $idSalesOrder): bool
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
    protected function getExternalTransactionLogEntry(int $idSalesOrder): ?SpyPaymentHeidelpayTransactionLog
    {
        $transactionLogQuery = $this->getQueryContainer()->queryExternalResponseTransactionLog($idSalesOrder);

        return $transactionLogQuery->findOne();
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog $externalTransactionLog
     *
     * @return bool
     */
    protected function isTransactionSuccessful(SpyPaymentHeidelpayTransactionLog $externalTransactionLog): bool
    {
        return $externalTransactionLog->getResponseCode() === HeidelpayConfig::EXTERNAL_RESPONSE_TRANSACTION_STATUS_OK;
    }
}
