<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistrationQuery;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotificationQuery;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayQuery;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper\HeidelpayPersistenceMapper;

/**
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayPersistenceFactory getFactory()
 */
class HeidelpayEntityManager extends AbstractEntityManager implements HeidelpayEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayPaymentTransfer $heidelpayPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentTransfer
     */
    public function savePaymentHeidelpayEntity(HeidelpayPaymentTransfer $heidelpayPaymentTransfer): HeidelpayPaymentTransfer
    {
        $paymentHeidelpayEntity = $this->getPaymentHeidelpayQuery()
            ->filterByFkSalesOrder($heidelpayPaymentTransfer->getFkSalesOrder())
            ->findOneOrCreate();

        $paymentHeidelpayEntity->fromArray(
            $heidelpayPaymentTransfer->modifiedToArray(),
        );
        $paymentHeidelpayEntity->save();

        return $this->getMapper()
            ->mapEntityToHeidelpayPaymentTransfer(
                $paymentHeidelpayEntity,
                $heidelpayPaymentTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $heidelpayNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function savePaymentHeidelpayNotificationEntity(
        HeidelpayNotificationTransfer $heidelpayNotificationTransfer
    ): HeidelpayNotificationTransfer {
        $paymentHeidelpayNotificationEntity = $this->getPaymentHeidelpayNotificationQuery()
            ->filterByUniqueId($heidelpayNotificationTransfer->getUniqueId())
            ->findOneOrCreate();

        $paymentHeidelpayNotificationEntity->fromArray(
            $heidelpayNotificationTransfer->modifiedToArray(),
        );
        $paymentHeidelpayNotificationEntity->save();

        return $this->getMapper()
            ->mapEntityToHeidelpayNotificationTransfer(
                $paymentHeidelpayNotificationEntity,
                $heidelpayNotificationTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function savePaymentHeidelpayDirectDebitRegistrationEntity(
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        $paymentHeidelpayDirectDebitRegistrationEntity = $this->getPaymentHeidelpayDirectDebitRegistrationQuery()
            ->filterByRegistrationUniqueId($directDebitRegistrationTransfer->getRegistrationUniqueId())
            ->findOneOrCreate();

        $paymentHeidelpayDirectDebitRegistrationEntity->fromArray(
            $directDebitRegistrationTransfer->getAccountInfo()->modifiedToArray(),
        );
        $paymentHeidelpayDirectDebitRegistrationEntity
            ->setFkCustomerAddress($directDebitRegistrationTransfer->getIdCustomerAddress())
            ->setRegistrationUniqueId($directDebitRegistrationTransfer->getRegistrationUniqueId())
            ->setTransactionId($directDebitRegistrationTransfer->getTransactionId());

        $paymentHeidelpayDirectDebitRegistrationEntity->save();

        return $this->getMapper()
            ->mapEntityToHeidelpayDirectDebitRegistrationTransfer(
                $paymentHeidelpayDirectDebitRegistrationEntity,
                $directDebitRegistrationTransfer,
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
