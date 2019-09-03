<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment;

use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayEntityManagerInterface;

class PaymentWriter implements PaymentWriterInterface
{
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
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function updateHeidelpayPaymentWithResponse(HeidelpayResponseTransfer $responseTransfer): void
    {
        $heidelpayPaymentTransfer = $this->createHeidelpayPaymentTransfer($responseTransfer);
        $this->entityManager->savePaymentHeidelpayEntity($heidelpayPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentTransfer
     */
    protected function createHeidelpayPaymentTransfer(HeidelpayResponseTransfer $responseTransfer): HeidelpayPaymentTransfer
    {
        return (new HeidelpayPaymentTransfer())
            ->setFkSalesOrder($responseTransfer->getIdSalesOrder())
            ->setIdPaymentReference($this->getIdPaymentReferenceFromResponse($responseTransfer))
            ->setConnectorInvoiceAccountInfo($responseTransfer->getConnectorInvoiceAccountInfo());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return string|null
     */
    protected function getIdPaymentReferenceFromResponse(HeidelpayResponseTransfer $responseTransfer): ?string
    {
        return $responseTransfer->getIdPaymentReference() ?? $responseTransfer->getIdTransactionUnique();
    }
}
