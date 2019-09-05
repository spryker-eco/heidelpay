<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\Registration;

use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface;

class DirectDebitRegistrationReader implements DirectDebitRegistrationReaderInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface
     */
    protected $repository;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface $repository
     */
    public function __construct(HeidelpayRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function getLastSuccessfulRegistration(QuoteTransfer $quoteTransfer): HeidelpayDirectDebitRegistrationTransfer
    {
        $lastSuccessfulRegistration = $this->repository
            ->findLastHeidelpayDirectDebitRegistrationByIdCustomerAddress(
                $quoteTransfer->getShippingAddress()->getIdCustomerAddress()
            );

        if ($lastSuccessfulRegistration === null) {
            return new HeidelpayDirectDebitRegistrationTransfer();
        }

        return $lastSuccessfulRegistration;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function retrieveDirectDebitRegistration(
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        $directDebitRegistration = $this->repository
            ->findHeidelpayDirectDebitRegistrationByIdAndTransactionId(
                $directDebitRegistrationTransfer->getIdDirectDebitRegistration(),
                $directDebitRegistrationTransfer->getTransactionId()
            );

        if ($directDebitRegistration === null) {
            return new HeidelpayDirectDebitRegistrationTransfer();
        }

        return $directDebitRegistration;
    }
}
