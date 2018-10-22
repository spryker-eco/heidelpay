<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction;

use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;

interface TransactionLogReaderInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    public function findOrderAuthorizeTransactionLogByIdSalesOrder(int $idSalesOrder): HeidelpayTransactionLogTransfer;

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    public function findOrderAuthorizeTransactionLogByOrderReference(string $orderReference): HeidelpayTransactionLogTransfer;

    /**
     * @param int $orderReference
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer|null
     */
    public function findOrderDebitTransactionLog(int $orderReference): ?HeidelpayTransactionLogTransfer;
}
