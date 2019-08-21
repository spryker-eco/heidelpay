<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentTransfer;

interface HeidelpayRepositoryInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentTransfer|null
     */
    public function findHeidelpayPaymentByIdSalesOrder(int $idSalesOrder): ?HeidelpayPaymentTransfer;

    /**
     * @param string $uniqueId
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer|null
     */
    public function findPaymentHeidelpayNotificationByUniqueId(string $uniqueId): ?HeidelpayNotificationTransfer;

    /**
     * @param string $transactionId
     * @param string $paymentCode
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer|null
     */
    public function findPaymentHeidelpayNotificationByTransactionIdAndPaymentCode(
        string $transactionId,
        string $paymentCode
    ): ?HeidelpayNotificationTransfer;
}
