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
     * - Queries payment by id sales order
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
     * - Queries capture transaction log
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
     * - Queries reservation transaction log
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryReservationTransactionLog($idSalesOrder);

    /**
     * Specification:
     * - Queries transaction by id sales order and type
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
     * - Queries external response transaction log
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
     * - Queries credit card registration by registration number
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
     * - Queries latest registration by id shipping address
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
     * - Queries registration by id and quote hash
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
     * - Queries finalize transaction log
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function queryFinalizeTransactionLog($idSalesOrder): SpyPaymentHeidelpayTransactionLogQuery;
}
