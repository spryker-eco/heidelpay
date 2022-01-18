<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
            ->filterByTransactionType(HeidelpayConfig::TRANSACTION_TYPE_RESERVATION);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryFinalizeTransactionLog($idSalesOrder): SpyPaymentHeidelpayTransactionLogQuery
    {
        return $this->getFactory()
            ->createPaymentHeidelpayTransactionLogQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->filterByTransactionType(HeidelpayConfig::TRANSACTION_TYPE_FINALIZE);
    }

    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
