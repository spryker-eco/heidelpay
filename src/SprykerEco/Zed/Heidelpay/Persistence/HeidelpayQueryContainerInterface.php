<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistrationQuery;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayQuery;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface HeidelpayQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayQuery
     */
    public function queryPaymentByIdSalesOrder(int $idSalesOrder): SpyPaymentHeidelpayQuery;

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryCaptureTransactionLog(int $idSalesOrder): SpyPaymentHeidelpayTransactionLogQuery;

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryReservationTransactionLog($idSalesOrder);

    /**
     * @api
     *
     * @param int $idSalesOrder
     * @param string $transactionType
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryTransactionByIdSalesOrderAndType(int $idSalesOrder, string $transactionType): SpyPaymentHeidelpayTransactionLogQuery;

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryExternalResponseTransactionLog(int $idSalesOrder): SpyPaymentHeidelpayTransactionLogQuery;

    /**
     * @api
     *
     * @param string $registrationNumber
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistrationQuery
     */
    public function queryCreditCardRegistrationByRegistrationNumber(string $registrationNumber): SpyPaymentHeidelpayCreditCardRegistrationQuery;

    /**
     * @api
     *
     * @param int $idAddress
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistrationQuery
     */
    public function queryLatestRegistrationByIdShippingAddress(int $idAddress): SpyPaymentHeidelpayCreditCardRegistrationQuery;

    /**
     * @api
     *
     * @param int $idRegistration
     * @param string $quoteHash
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistrationQuery
     */
    public function queryRegistrationByIdAndQuoteHash(int $idRegistration, string $quoteHash): SpyPaymentHeidelpayCreditCardRegistrationQuery;

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryFinalizeTransactionLog($idSalesOrder): SpyPaymentHeidelpayTransactionLogQuery;

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryCaptureSuccessTransactionLog(int $idSalesOrder): SpyPaymentHeidelpayTransactionLogQuery;
}
