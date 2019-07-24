<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper\HeidelpayPersistenceMapper;

/**
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayPersistenceFactory getFactory()
 */
class HeidelpayEntityManager extends AbstractEntityManager implements HeidelpayEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $heidelpayNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function savePaymentHeidelpayNotificationEntity(
        HeidelpayNotificationTransfer $heidelpayNotificationTransfer
    ): HeidelpayNotificationTransfer {
        $paymentHeidelpayNotificationEntity = $this->getFactory()
            ->createPaymentHeidelpayNotificationQuery()
            ->filterByTransactionId($heidelpayNotificationTransfer->getTransactionId())
            ->findOneOrCreate();

        $paymentHeidelpayNotificationEntity->fromArray(
            $heidelpayNotificationTransfer->modifiedToArray()
        );
        $paymentHeidelpayNotificationEntity->save();

        return $this->getMapper()
            ->mapEntityToHeidelpayNotificationTransfer(
                $paymentHeidelpayNotificationEntity,
                $heidelpayNotificationTransfer
            );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper\HeidelpayPersistenceMapper
     */
    protected function getMapper(): HeidelpayPersistenceMapper
    {
        return $this->getFactory()->createHeidelpayPersistenceMapper();
    }
}
