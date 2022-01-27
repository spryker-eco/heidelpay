<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayNotificationCollectionTransfer;
use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Generated\Shared\Transfer\PaymentHeidelpayTransactionLogCriteriaTransfer;

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
     * @return \Generated\Shared\Transfer\HeidelpayNotificationCollectionTransfer
     */
    public function getPaymentHeidelpayNotificationCollectionByTransactionIdAndPaymentCode(
        string $transactionId,
        string $paymentCode
    ): HeidelpayNotificationCollectionTransfer;

    /**
     * @param string $registrationUniqueId
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer|null
     */
    public function findHeidelpayDirectDebitRegistrationByRegistrationUniqueId(
        string $registrationUniqueId
    ): ?HeidelpayDirectDebitRegistrationTransfer;

    /**
     * @param int $idCustomerAddress
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer|null
     */
    public function findLastHeidelpayDirectDebitRegistrationByIdCustomerAddress(
        int $idCustomerAddress
    ): ?HeidelpayDirectDebitRegistrationTransfer;

    /**
     * @param int $idRegistration
     * @param string $transactionId
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer|null
     */
    public function findHeidelpayDirectDebitRegistrationByIdAndTransactionId(
        int $idRegistration,
        string $transactionId
    ): ?HeidelpayDirectDebitRegistrationTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentHeidelpayTransactionLogCriteriaTransfer $paymemtHeidelpayTransactionLogCriteriaTransfer
     *
     * @return bool
     */
    public function hasPaymentHeidelpayTransactionLog(PaymentHeidelpayTransactionLogCriteriaTransfer $paymemtHeidelpayTransactionLogCriteriaTransfer): bool;
}
