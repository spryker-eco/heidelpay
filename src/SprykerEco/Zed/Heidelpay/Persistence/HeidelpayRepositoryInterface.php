<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;

interface HeidelpayRepositoryInterface
{
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
     * @param int $idSalesOrder
     * @param string $transactionType
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer|null
     */
    public function findHeidelpayTransactionLogByIdSalesOrderAndTransactionType(
        int $idSalesOrder,
        string $transactionType
    ): ?HeidelpayTransactionLogTransfer;
}
