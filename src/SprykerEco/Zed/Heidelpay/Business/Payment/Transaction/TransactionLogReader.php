<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction;

use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Zed\Heidelpay\Business\Adapter\TransactionParserInterface;
use SprykerEco\Zed\Heidelpay\Business\Order\OrderReaderInterface;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface;

class TransactionLogReader implements TransactionLogReaderInterface
{
    const TRANSACTION_TYPE_AUTHORIZE = HeidelpayConstants::TRANSACTION_TYPE_AUTHORIZE;
    const TRANSACTION_TYPE_DEBIT = HeidelpayConstants::TRANSACTION_TYPE_DEBIT;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Adapter\TransactionParserInterface
     */
    protected $transactionAdapter;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Order\OrderReaderInterface
     */
    protected $orderReader;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Heidelpay\Business\Adapter\TransactionParserInterface $transactionAdapter
     * @param \SprykerEco\Zed\Heidelpay\Business\Order\OrderReaderInterface $orderReader
     */
    public function __construct(
        HeidelpayQueryContainerInterface $queryContainer,
        TransactionParserInterface $transactionAdapter,
        OrderReaderInterface $orderReader
    ) {
        $this->queryContainer = $queryContainer;
        $this->transactionAdapter = $transactionAdapter;
        $this->orderReader = $orderReader;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer|null
     */
    public function findOrderDebitTransactionLog($idSalesOrder)
    {
        $spyTransactionLog = $this->findOrderDebitTransactionEntity($idSalesOrder);

        if ($spyTransactionLog === null) {
            return null;
        }

        return $this->buildTransactionTransfer($spyTransactionLog);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer|null
     */
    public function findOrderAuthorizeTransactionLogByIdSalesOrder($idSalesOrder)
    {
        $spyTransactionLog = $this->findOrderAuthorizeTransactionEntity($idSalesOrder);

        if ($spyTransactionLog === null) {
            return null;
        }

        return $this->buildTransactionTransfer($spyTransactionLog);
    }

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer|null
     */
    public function findOrderAuthorizeTransactionLogByOrderReference($orderReference)
    {
        $idSalesOrder = $this->orderReader->getOrderIdByReference($orderReference);

        return $this->findOrderAuthorizeTransactionLogByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog|null
     */
    protected function findOrderAuthorizeTransactionEntity($idSalesOrder)
    {
        $transactionLogEntity = $this
            ->queryContainer
            ->queryTransactionByIdSalesOrderAndType(
                $idSalesOrder,
                static::TRANSACTION_TYPE_AUTHORIZE
            )
            ->findOne();

        return $transactionLogEntity;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog|null
     */
    protected function findOrderDebitTransactionEntity($idSalesOrder)
    {
        $transactionLogEntity = $this
            ->queryContainer
            ->queryTransactionByIdSalesOrderAndType(
                $idSalesOrder,
                static::TRANSACTION_TYPE_DEBIT
            )
            ->findOne();

        return $transactionLogEntity;
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog $transactionLogEntry
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    protected function buildTransactionTransfer(SpyPaymentHeidelpayTransactionLog $transactionLogEntry)
    {
        $transactionLogTransfer = new HeidelpayTransactionLogTransfer();
        $transactionLogTransfer->fromArray($transactionLogEntry->toArray(), true);

        $transactionLogTransfer = $this->hydrateHeidelpayPayloadTransfer($transactionLogTransfer);

        return $transactionLogTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $transactionLogTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    protected function hydrateHeidelpayPayloadTransfer($transactionLogTransfer)
    {
        $payloadTransfer = $this->transactionAdapter->getHeidelpayResponseTransfer($transactionLogTransfer);
        $transactionLogTransfer->setHeidelpayResponse($payloadTransfer);

        return $transactionLogTransfer;
    }
}
