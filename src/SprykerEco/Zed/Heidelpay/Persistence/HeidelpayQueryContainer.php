<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;

/**
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayPersistenceFactory getFactory()
 */
class HeidelpayQueryContainer extends AbstractQueryContainer implements HeidelpayQueryContainerInterface
{

    const TRANSACTION_TYPE_EXTERNAL_RESPONSE = HeidelpayConstants::TRANSACTION_TYPE_EXTERNAL_RESPONSE;
    const TRANSACTION_TYPE_CAPTURE = HeidelpayConstants::TRANSACTION_TYPE_CAPTURE;

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
            ->filterByTransactionType(static::TRANSACTION_TYPE_EXTERNAL_RESPONSE);
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
            ->filterByTransactionType(static::TRANSACTION_TYPE_CAPTURE);
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
    public function queryCreditCardRegistrationByIdRegistration($registrationNumber)
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

}
