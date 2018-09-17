<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

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
    public function queryExternalResponseTransactionLog($idSalesOrder)
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
    public function queryCaptureTransactionLog($idSalesOrder)
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
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryReservationTransactionLog($idSalesOrder)
    {
        return $this->getFactory()
            ->createPaymentHeidelpayTransactionLogQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->filterByTransactionType(HeidelpayConfig::TRANSACTION_TYPE_AUTHORIZE_ON_REGISTRATION);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryFinalizeTransactionLog($idSalesOrder)
    {
        return $this->getFactory()
            ->createPaymentHeidelpayTransactionLogQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->filterByTransactionType(HeidelpayConfig::TRANSACTION_TYPE_FINALIZE);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayQuery
     */
    public function queryPaymentByIdSalesOrder($idSalesOrder)
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
    public function queryTransactionByIdSalesOrderAndType($idSalesOrder, $transactionType)
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
    public function queryCreditCardRegistrationByRegistrationNumber($registrationNumber)
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
    public function queryLatestRegistrationByIdShippingAddress($idAddress)
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
    public function queryRegistrationByIdAndQuoteHash($idRegistration, $quoteHash)
    {
        return $this->getFactory()
            ->createHeidelpayCreditCardRegistrationQuery()
            ->filterByIdCreditCardRegistration($idRegistration)
            ->filterByQuoteHash($quoteHash);
    }
}
