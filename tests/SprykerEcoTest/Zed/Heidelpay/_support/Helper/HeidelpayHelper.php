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
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class HeidelpayHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    public function haveHeidelpayTransactionLog(array $seedData = []): HeidelpayTransactionLogTransfer
    {
        $heidelpayTransactionLogTransfer = (new HeidelpayTransactionLogBuilder($seedData))->build();

        $paymentHeidelpayTransactionLogEntity = new SpyPaymentHeidelpayTransactionLog();
        $paymentHeidelpayTransactionLogEntity->fromArray(
            $heidelpayTransactionLogTransfer->toArray(),
        );
        $paymentHeidelpayTransactionLogEntity->setFkSalesOrder($heidelpayTransactionLogTransfer->getIdSalesOrder());
        $paymentHeidelpayTransactionLogEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($paymentHeidelpayTransactionLogEntity): void {
            $paymentHeidelpayTransactionLogEntity->delete();
        });

        $heidelpayTransactionLogTransfer->fromArray(
            $paymentHeidelpayTransactionLogEntity->toArray(),
            true,
        )->setIdSalesOrder($paymentHeidelpayTransactionLogEntity->getFkSalesOrder());

        return $heidelpayTransactionLogTransfer;
    }
}
