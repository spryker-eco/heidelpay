<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\Registration;

use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayEntityManagerInterface;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface;

class DirectDebitRegistrationWriter implements DirectDebitRegistrationWriterInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface
     */
    protected $repository;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayEntityManagerInterface $entityManager
     * @param \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface $repository
     */
    public function __construct(
        HeidelpayEntityManagerInterface $entityManager,
        HeidelpayRepositoryInterface $repository
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function createDirectDebitRegistration(
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        return $this->getTransactionHandler()->handleTransaction(
            function () use ($directDebitRegistrationTransfer) {
                return $this->savePaymentHeidelpayDirectDebitRegistrationEntity($directDebitRegistrationTransfer);
            },
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function updateDirectDebitRegistration(QuoteTransfer $quoteTransfer): HeidelpayDirectDebitRegistrationTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(
            function () use ($quoteTransfer) {
                $directDebitRegistrationTransfer = $this->getDirectDebitRegistration($quoteTransfer);

                if ($directDebitRegistrationTransfer->getIdDirectDebitRegistration() === null) {
                    return $directDebitRegistrationTransfer;
                }

                $directDebitRegistrationTransfer->setIdCustomerAddress(
                    $quoteTransfer->getShippingAddress()->getIdCustomerAddress(),
                );

                return $this->savePaymentHeidelpayDirectDebitRegistrationEntity($directDebitRegistrationTransfer);
            },
        );
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    protected function savePaymentHeidelpayDirectDebitRegistrationEntity(
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        return $this->entityManager->savePaymentHeidelpayDirectDebitRegistrationEntity($directDebitRegistrationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    protected function getDirectDebitRegistration(QuoteTransfer $quoteTransfer): HeidelpayDirectDebitRegistrationTransfer
    {
        $transactionUniqueId = $quoteTransfer
            ->getPayment()
            ->getHeidelpayDirectDebit()
            ->getSelectedRegistration()
            ->getRegistrationUniqueId();

        $directDebitRegistrationTransfer = $this->repository
            ->findHeidelpayDirectDebitRegistrationByRegistrationUniqueId($transactionUniqueId);

        if ($directDebitRegistrationTransfer === null) {
            return new HeidelpayDirectDebitRegistrationTransfer();
        }

        return $directDebitRegistrationTransfer;
    }
}
