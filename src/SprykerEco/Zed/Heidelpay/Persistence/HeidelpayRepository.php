<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotificationQuery;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
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
}
