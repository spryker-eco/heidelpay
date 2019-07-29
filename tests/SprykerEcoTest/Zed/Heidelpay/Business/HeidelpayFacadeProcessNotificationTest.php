<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotification;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayNotificationQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Propel\Runtime\Propel;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\SuccessfulResponseHeidelpayBusinessFactory;
use SprykerEcoTest\Zed\Heidelpay\Business\Mock\UnsuccesfulResponseHeidelpayBusinessFactory;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeAuthorizePaymentTest
 */
class HeidelpayFacadeProcessNotificationTest extends HeidelpayPaymentTest
{
    protected const NOTIFICATION_TIMESTAMP = '2019-07-09 12:34:45';
    protected const NOTIFICATION_RETRIES = 1;

    /**
     * @return void
     */
    public function testProcessNotification(): void
    {
        //Arrange
        $notificationTransfer = $this->createHeidelpayNotificationTransfer();

        //Act
        $result = $this->heidelpayFacade->processNotification($notificationTransfer);
        $entity = $this->getNotificationEntity($result);

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
    protected function getNotificationEntity(HeidelpayNotificationTransfer $notificationTransfer): ?SpyPaymentHeidelpayNotification
    {
        $query = SpyPaymentHeidelpayNotificationQuery::create();
        $entity = $query
            ->filterByTransactionId($notificationTransfer->getTransactionId())
            ->filterByUniqueId($notificationTransfer->getUniqueId())
            ->findOne();

        return $entity;

    }
}
