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
     * Specification:
     * - Get Heidelpay payment query by sales order.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayQuery
     */
    public function queryPaymentByIdSalesOrder(int $idSalesOrder): SpyPaymentHeidelpayQuery;

    /**
     * Specification:
     * - Get Heidelpay transaction log query by sales order.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryCaptureTransactionLog(int $idSalesOrder): SpyPaymentHeidelpayTransactionLogQuery;

    /**
     * Specification:
     * - Get Heidelpay reservation transaction log query by sales order.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryReservationTransactionLog(int $idSalesOrder): SpyPaymentHeidelpayTransactionLogQuery;

    /**
     * Specification:
     * - Get Heidelpay transaction query by sales order id and transaction type.
     *
     * @api
     *
     * @param int $idSalesOrder
     * @param string $transactionType
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryTransactionByIdSalesOrderAndType(int $idSalesOrder, string $transactionType): SpyPaymentHeidelpayTransactionLogQuery;

    /**
     * Specification:
     * - Get Heidelpay external response transaction log query by sales order id.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryExternalResponseTransactionLog(int $idSalesOrder): SpyPaymentHeidelpayTransactionLogQuery;

    /**
     * Specification:
     * - Get Heidelpay credit card registration query by registration number.
     *
     * @api
     *
     * @param string $registrationNumber
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistrationQuery
     */
    public function queryCreditCardRegistrationByRegistrationNumber(string $registrationNumber): SpyPaymentHeidelpayCreditCardRegistrationQuery;

    /**
     * Specification:
     * - Get Heidelpay latest registration query by shipping address id.
     *
     * @api
     *
     * @param int $idAddress
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistrationQuery
     */
    public function queryLatestRegistrationByIdShippingAddress(int $idAddress): SpyPaymentHeidelpayCreditCardRegistrationQuery;

    /**
     * Specification:
     * - Get Heidelpay registration query by id and qoute hash.
     *
     * @api
     *
     * @param int $idRegistration
     * @param string $quoteHash
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistrationQuery
     */
    public function queryRegistrationByIdAndQuoteHash(int $idRegistration, string $quoteHash): SpyPaymentHeidelpayCreditCardRegistrationQuery;

    /**
     * Specification:
     * - Get Heidelpay finalize transaction log query by sales order id.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryFinalizeTransactionLog(int $idSalesOrder): SpyPaymentHeidelpayTransactionLogQuery;
}
