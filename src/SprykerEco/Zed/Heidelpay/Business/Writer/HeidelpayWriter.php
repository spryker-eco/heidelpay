<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Writer;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayEntityManagerInterface;

class HeidelpayWriter implements HeidelpayWriterInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayEntityManagerInterface $entityManager
     */
    public function __construct(HeidelpayEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     *
     * @return void
     */
    public function createNotificationEntity(HeidelpayNotificationTransfer $notificationTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(
            function () use ($notificationTransfer) {
                $this->entityManager->savePaymentHeidelpayNotificationEntity($notificationTransfer);
            },
        );
    }
}
