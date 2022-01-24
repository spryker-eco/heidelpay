<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\HeidelpayTransactionLogBuilder;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery;

class HeidelpayHelper extends Module
{
    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    public function haveHeidelpayTransactionLog(array $seedData = []): HeidelpayTransactionLogTransfer
    {
        $this->cleanUpSpyPaymentHeidelpayTransactionLogTable();

        $heidelpayTransactionLogTransfer = (new HeidelpayTransactionLogBuilder($seedData))->build();

        $paymentHeidelpayTransactionLogEntity = new SpyPaymentHeidelpayTransactionLog();
        $paymentHeidelpayTransactionLogEntity->fromArray(
            $heidelpayTransactionLogTransfer->toArray(),
        );
        $paymentHeidelpayTransactionLogEntity->setFkSalesOrder($heidelpayTransactionLogTransfer->getIdSalesOrder());
        $paymentHeidelpayTransactionLogEntity->save();

        $heidelpayTransactionLogTransfer->fromArray(
            $paymentHeidelpayTransactionLogEntity->toArray(),
            true,
        )->setIdSalesOrder($paymentHeidelpayTransactionLogEntity->getFkSalesOrder());

        return $heidelpayTransactionLogTransfer;
    }

    /**
     * @return void
     */
    protected function cleanUpSpyPaymentHeidelpayTransactionLogTable(): void
    {
        SpyPaymentHeidelpayTransactionLogQuery::create()->deleteAll();
    }
}
