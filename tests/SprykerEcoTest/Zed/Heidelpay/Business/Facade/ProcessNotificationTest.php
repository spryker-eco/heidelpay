<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotification;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotificationQuery;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group Facade
 * @group ProcessNotificationTest
 */
class ProcessNotificationTest extends HeidelpayPaymentTest
{
    /**
     * @var string
     */
    protected const NOTIFICATION_TIMESTAMP = '2019-07-09 12:34:45';

    /**
     * @var int
     */
    protected const NOTIFICATION_RETRIES = 1;

    /**
     * @return void
     */
    public function testProcessNotification(): void
    {
        //Arrange
        $notificationTransfer = $this->createHeidelpayNotificationTransfer();

        //Act
        $heidelpayNotificationTransfer = $this->heidelpayFacade->processNotification($notificationTransfer);
        $entity = $this->findNotificationEntity($heidelpayNotificationTransfer);

        //Assert
        $this->assertNotNull($entity);
        $this->assertNotEmpty($entity->getTransactionId());
        $this->assertNotEmpty($entity->getUniqueId());
        $this->assertNotEmpty($entity->getResult());
        $this->assertNotEmpty($entity->getResultCode());
        $this->assertNotEmpty($entity->getPaymentCode());
    }

    /**
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    protected function createHeidelpayNotificationTransfer(): HeidelpayNotificationTransfer
    {
        return (new HeidelpayNotificationTransfer())
            ->setNotificationBody($this->tester->getNotificationBody())
            ->setTimestamp(static::NOTIFICATION_TIMESTAMP)
            ->setRetries(static::NOTIFICATION_RETRIES);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotification|null
     */
    protected function findNotificationEntity(HeidelpayNotificationTransfer $notificationTransfer): ?SpyPaymentHeidelpayNotification
    {
        $query = SpyPaymentHeidelpayNotificationQuery::create();
        $entity = $query
            ->filterByTransactionId($notificationTransfer->getTransactionId())
            ->filterByUniqueId($notificationTransfer->getUniqueId())
            ->findOne();

        return $entity;
    }
}
