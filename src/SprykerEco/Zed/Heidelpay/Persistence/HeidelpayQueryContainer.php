<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistrationQuery;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayQuery;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;

/**
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayPersistenceFactory getFactory()
 */
class HeidelpayQueryContainer extends AbstractQueryContainer implements HeidelpayQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryExternalResponseTransactionLog(int $idSalesOrder): SpyPaymentHeidelpayTransactionLogQuery
    {
        return $this->getFactory()
            ->createPaymentHeidelpayTransactionLogQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->filterByTransactionType(HeidelpayConfig::TRANSACTION_TYPE_EXTERNAL_RESPONSE);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryCaptureTransactionLog(int $idSalesOrder): SpyPaymentHeidelpayTransactionLogQuery
    {
        return $this->getFactory()
            ->createPaymentHeidelpayTransactionLogQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->filterByTransactionType(HeidelpayConfig::TRANSACTION_TYPE_CAPTURE);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayQuery
     */
    public function queryPaymentByIdSalesOrder(int $idSalesOrder): SpyPaymentHeidelpayQuery
    {
        return $this
            ->getFactory()
            ->createPaymentHeidelpayQuery()
            ->filterByFkSalesOrder($idSalesOrder);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     * @param string $transactionType
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryTransactionByIdSalesOrderAndType(int $idSalesOrder, string $transactionType): SpyPaymentHeidelpayTransactionLogQuery
    {
        return $this->getFactory()
            ->createPaymentHeidelpayTransactionLogQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->filterByTransactionType($transactionType);
    }

    /**
     * @api
     *
     * @param string $registrationNumber
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistrationQuery
     */
    public function queryCreditCardRegistrationByRegistrationNumber(string $registrationNumber): SpyPaymentHeidelpayCreditCardRegistrationQuery
    {
        return $this->getFactory()
            ->createHeidelpayCreditCardRegistrationQuery()
            ->filterByRegistrationNumber($registrationNumber);
    }

    /**
     * @api
     *
     * @param int $idAddress
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistrationQuery
     */
    public function queryLatestRegistrationByIdShippingAddress(int $idAddress): SpyPaymentHeidelpayCreditCardRegistrationQuery
    {
        return $this->getFactory()
            ->createHeidelpayCreditCardRegistrationQuery()
            ->filterByFkCustomerAddress($idAddress)
            ->orderByIdCreditCardRegistration(Criteria::DESC);
    }

    /**
     * @api
     *
     * @param int $idRegistration
     * @param string $quoteHash
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistrationQuery
     */
    public function queryRegistrationByIdAndQuoteHash(int $idRegistration, string $quoteHash): SpyPaymentHeidelpayCreditCardRegistrationQuery
    {
        return $this->getFactory()
            ->createHeidelpayCreditCardRegistrationQuery()
            ->filterByIdCreditCardRegistration($idRegistration)
            ->filterByQuoteHash($quoteHash);
    }
}
