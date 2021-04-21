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
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistrationQuery;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotificationQuery;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper\HeidelpayPersistenceMapper;

/**
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayPersistenceFactory getFactory()
 */
class HeidelpayRepository extends AbstractRepository implements HeidelpayRepositoryInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentTransfer|null
     */
    public function findHeidelpayPaymentByIdSalesOrder(int $idSalesOrder): ?HeidelpayPaymentTransfer
    {
        $paymentHeidelpayEntity = $this->getPaymentHeidelpayQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->findOne();

        if ($paymentHeidelpayEntity === null) {
            return null;
        }

        return $this->getMapper()
            ->mapEntityToHeidelpayPaymentTransfer(
                $paymentHeidelpayEntity,
                new HeidelpayPaymentTransfer()
            );
    }

    /**
     * @param string $uniqueId
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer|null
     */
    public function findPaymentHeidelpayNotificationByUniqueId(string $uniqueId): ?HeidelpayNotificationTransfer
    {
        $paymentHeidelpayNotification = $this->getPaymentHeidelpayNotificationQuery()
            ->filterByUniqueId($uniqueId)
            ->findOne();

        if ($paymentHeidelpayNotification === null) {
            return null;
        }

        return $this->getMapper()
            ->mapEntityToHeidelpayNotificationTransfer(
                $paymentHeidelpayNotification,
                new HeidelpayNotificationTransfer()
            );
    }

    /**
     * @param string $transactionId
     * @param string $paymentCode
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationCollectionTransfer
     */
    public function getPaymentHeidelpayNotificationCollectionByTransactionIdAndPaymentCode(
        string $transactionId,
        string $paymentCode
    ): HeidelpayNotificationCollectionTransfer {
        $paymentHeidelpayNotification = $this->getPaymentHeidelpayNotificationQuery()
            ->filterByTransactionId($transactionId)
            ->filterByPaymentCode($paymentCode)
            ->find();

        return $this->getMapper()
            ->mapNotificationEntitiesToHeidelpayNotificationCollection(
                $paymentHeidelpayNotification,
                new HeidelpayNotificationCollectionTransfer()
            );
    }

    /**
     * @param string $registrationUniqueId
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer|null
     */
    public function findHeidelpayDirectDebitRegistrationByRegistrationUniqueId(
        string $registrationUniqueId
    ): ?HeidelpayDirectDebitRegistrationTransfer {
        $paymentHeidelpayDirectDebitRegistrationEntity = $this->getPaymentHeidelpayDirectDebitRegistrationQuery()
            ->filterByRegistrationUniqueId($registrationUniqueId)
            ->findOne();

        if ($paymentHeidelpayDirectDebitRegistrationEntity === null) {
            return null;
        }

        return $this->getMapper()
            ->mapEntityToHeidelpayDirectDebitRegistrationTransfer(
                $paymentHeidelpayDirectDebitRegistrationEntity,
                new HeidelpayDirectDebitRegistrationTransfer()
            );
    }

    /**
     * @param int $idCustomerAddress
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer|null
     */
    public function findLastHeidelpayDirectDebitRegistrationByIdCustomerAddress(
        int $idCustomerAddress
    ): ?HeidelpayDirectDebitRegistrationTransfer {
        $paymentHeidelpayDirectDebitRegistrationEntity = $this->getPaymentHeidelpayDirectDebitRegistrationQuery()
            ->filterByFkCustomerAddress($idCustomerAddress)
            ->orderByIdDirectDebitRegistration(Criteria::DESC)
            ->findOne();

        if ($paymentHeidelpayDirectDebitRegistrationEntity === null) {
            return null;
        }

        return $this->getMapper()
            ->mapEntityToHeidelpayDirectDebitRegistrationTransfer(
                $paymentHeidelpayDirectDebitRegistrationEntity,
                new HeidelpayDirectDebitRegistrationTransfer()
            );
    }

    /**
     * @param int $idRegistration
     * @param string $transactionId
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer|null
     */
    public function findHeidelpayDirectDebitRegistrationByIdAndTransactionId(
        int $idRegistration,
        string $transactionId
    ): ?HeidelpayDirectDebitRegistrationTransfer {
        $paymentHeidelpayDirectDebitRegistrationEntity = $this->getPaymentHeidelpayDirectDebitRegistrationQuery()
            ->filterByIdDirectDebitRegistration($idRegistration)
            ->filterByTransactionId($transactionId)
            ->findOne();

        if ($paymentHeidelpayDirectDebitRegistrationEntity === null) {
            return null;
        }

        return $this->getMapper()
            ->mapEntityToHeidelpayDirectDebitRegistrationTransfer(
                $paymentHeidelpayDirectDebitRegistrationEntity,
                new HeidelpayDirectDebitRegistrationTransfer()
            );
    }

    /**
     * @param int $idSalesOrder
     * @param string $transactionType
     * @param string $responseCode
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer|null
     */
    public function findHeidelpayTransactionLogByIdSalesOrderAndTransactionTypeAndResponseCode(
        int $idSalesOrder,
        string $transactionType,
        string $responseCode
    ): ?HeidelpayTransactionLogTransfer {
        $paymentHeidelpayTransactionLogEntity = $this->getFactory()->createPaymentHeidelpayTransactionLogQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->filterByTransactionType($transactionType)
            ->filterByResponseCode($responseCode)
            ->findOne();

        if ($paymentHeidelpayTransactionLogEntity === null) {
            return null;
        }

        return $this->getMapper()
            ->mapEntityToHeidelpayTransactionLogTransfer(
                $paymentHeidelpayTransactionLogEntity,
                new HeidelpayTransactionLogTransfer()
            );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper\HeidelpayPersistenceMapper
     */
    protected function getMapper(): HeidelpayPersistenceMapper
    {
        return $this->getFactory()->createHeidelpayPersistenceMapper();
    }

    /**
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayQuery
     */
    protected function getPaymentHeidelpayQuery(): SpyPaymentHeidelpayQuery
    {
        return $this->getFactory()->createPaymentHeidelpayQuery();
    }

    /**
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotificationQuery
     */
    protected function getPaymentHeidelpayNotificationQuery(): SpyPaymentHeidelpayNotificationQuery
    {
        return $this->getFactory()->createPaymentHeidelpayNotificationQuery();
    }

    /**
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistrationQuery
     */
    protected function getPaymentHeidelpayDirectDebitRegistrationQuery(): SpyPaymentHeidelpayDirectDebitRegistrationQuery
    {
        return $this->getFactory()->createPaymentHeidelpayDirectDebitRegistrationQuery();
    }
}
