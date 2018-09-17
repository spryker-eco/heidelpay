<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction;

use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\Adapter\TransactionParserInterface;
use SprykerEco\Zed\Heidelpay\Business\Encrypter\EncrypterInterface;
use SprykerEco\Zed\Heidelpay\Business\Order\OrderReaderInterface;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface;

class TransactionLogReader implements TransactionLogReaderInterface
{
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
     * @var \SprykerEco\Zed\Heidelpay\Business\Encrypter\EncrypterInterface
     */
    protected $encrypter;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Heidelpay\Business\Adapter\TransactionParserInterface $transactionAdapter
     * @param \SprykerEco\Zed\Heidelpay\Business\Order\OrderReaderInterface $orderReader
     * @param \SprykerEco\Zed\Heidelpay\Business\Encrypter\EncrypterInterface $encrypter
     */
    public function __construct(
        HeidelpayQueryContainerInterface $queryContainer,
        TransactionParserInterface $transactionAdapter,
        OrderReaderInterface $orderReader,
        EncrypterInterface $encrypter
    ) {
        $this->queryContainer = $queryContainer;
        $this->transactionAdapter = $transactionAdapter;
        $this->orderReader = $orderReader;
        $this->encrypter = $encrypter;
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
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer|null
     */
    public function findOrderAuthorizeOnRegistrationTransactionLogByIdSalesOrder($idSalesOrder)
    {
        $spyTransactionLog = $this->findOrderAuthorizeOnRegistrationTransactionEntity($idSalesOrder);

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
    public function findQuoteInitializeTransactionLogByIdSalesOrder($idSalesOrder)
    {
        $spyTransactionLog = $this->findQuoteInitializeTransactionEntity($idSalesOrder);

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
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer|null
     */
    public function findOrderAuthorizeOnRegistrationTransactionLogByOrderReference($orderReference)
    {
        $idSalesOrder = $this->orderReader->getOrderIdByReference($orderReference);

        return $this->findOrderAuthorizeOnRegistrationTransactionLogByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer|null
     */
    public function findQuoteInitializeTransactionLogByOrderReference($orderReference)
    {
        $idSalesOrder = $this->orderReader->getOrderIdByReference($orderReference);

        return $this->findQuoteInitializeTransactionLogByIdSalesOrder($idSalesOrder);
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
                HeidelpayConfig::TRANSACTION_TYPE_AUTHORIZE
            )
            ->findOne();

        return $transactionLogEntity;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog|null
     */
    protected function findOrderAuthorizeOnRegistrationTransactionEntity($idSalesOrder)
    {
        $transactionLogEntity = $this
            ->queryContainer
            ->queryTransactionByIdSalesOrderAndType(
                $idSalesOrder,
                HeidelpayConfig::TRANSACTION_TYPE_AUTHORIZE_ON_REGISTRATION
            )
            ->findOne();

        return $transactionLogEntity;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog|null
     */
    protected function findQuoteInitializeTransactionEntity($idSalesOrder)
    {
        $transactionLogEntity = $this
            ->queryContainer
            ->queryTransactionByIdSalesOrderAndType(
                $idSalesOrder,
                HeidelpayConfig::TRANSACTION_TYPE_INITIALIZE
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
                HeidelpayConfig::TRANSACTION_TYPE_DEBIT
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
        $responsePayload = $this->prepareResponsePayload($transactionLogEntry);

        $transactionLogTransfer = (new HeidelpayTransactionLogTransfer())
            ->fromArray($transactionLogEntry->toArray(), true)
            ->setResponsePayload($responsePayload);

        return $this->hydrateHeidelpayPayloadTransfer($transactionLogTransfer);
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

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog $transactionLogEntry
     *
     * @return string
     */
    protected function prepareResponsePayload(SpyPaymentHeidelpayTransactionLog $transactionLogEntry)
    {
        $responsePayload = $transactionLogEntry->getResponsePayload();
        if ($responsePayload !== null) {
            $responsePayload = $this->encrypter
                ->decryptData(base64_decode($responsePayload));
        }

        return $responsePayload;
    }
}
