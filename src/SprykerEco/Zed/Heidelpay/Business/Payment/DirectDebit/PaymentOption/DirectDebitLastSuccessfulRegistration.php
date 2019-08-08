<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\PaymentOption;

use Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\Registration\DirectDebitRegistrationReaderInterface;

class DirectDebitLastSuccessfulRegistration implements DirectDebitPaymentOptionInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\Registration\DirectDebitRegistrationReaderInterface
     */
    protected $registrationReader;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\Registration\DirectDebitRegistrationReaderInterface $registrationReader
     */
    public function __construct(DirectDebitRegistrationReaderInterface $registrationReader)
    {
        $this->registrationReader = $registrationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer $paymentOptionsTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer
     */
    public function addPaymentOption(
        QuoteTransfer $quoteTransfer,
        HeidelpayDirectDebitPaymentOptionsTransfer $paymentOptionsTransfer
    ): HeidelpayDirectDebitPaymentOptionsTransfer {
        $lastSuccessfulRegistrationTransfer = $this->getLastSuccessfulRegistration($quoteTransfer);

        if ($lastSuccessfulRegistrationTransfer->getIdDirectDebitRegistration() === null) {
            return $paymentOptionsTransfer;
        }

        $paymentOptionsTransfer
            ->setLastSuccessfulRegistration($lastSuccessfulRegistrationTransfer)
            ->addOption($this->createPaymentOptionTransfer());

        return $paymentOptionsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isOptionAvailableForQuote(QuoteTransfer $quoteTransfer): bool
    {
        return $this->isRegisteredShippingAddressUsed($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    protected function getLastSuccessfulRegistration(QuoteTransfer $quoteTransfer): HeidelpayDirectDebitRegistrationTransfer
    {
        $lastSuccessfulRegistrationTransfer = $this->getLastSuccessfulRegistrationFromQuote($quoteTransfer);

        if ($lastSuccessfulRegistrationTransfer !== null) {
            return $lastSuccessfulRegistrationTransfer;
        }

        return $this->registrationReader->getLastSuccessfulRegistration($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer|null
     */
    protected function getLastSuccessfulRegistrationFromQuote(QuoteTransfer $quoteTransfer): ?HeidelpayDirectDebitRegistrationTransfer
    {
        if (!$this->isLastSuccessfulRegistrationExistsInQuote($quoteTransfer)) {
            return null;
        }

        $lastSuccessfulRegistrationTransfer = $quoteTransfer
            ->getPayment()
            ->getHeidelpayDirectDebit()
            ->getPaymentOptions()
            ->getLastSuccessfulRegistration();

        if ($this->isQuoteShippingAddressChanged($quoteTransfer, $lastSuccessfulRegistrationTransfer)) {
            return null;
        }

        return $lastSuccessfulRegistrationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isLastSuccessfulRegistrationExistsInQuote(QuoteTransfer $quoteTransfer): bool
    {
        $directDebitPaymentTransfer = $quoteTransfer
            ->getPayment()
            ->getHeidelpayDirectDebit();

        return $directDebitPaymentTransfer !== null
            && $directDebitPaymentTransfer->getPaymentOptions() !== null
            && $directDebitPaymentTransfer->getPaymentOptions()->getLastSuccessfulRegistration() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $lastRegistrationTransfer
     *
     * @return bool
     */
    protected function isQuoteShippingAddressChanged(
        QuoteTransfer $quoteTransfer,
        HeidelpayDirectDebitRegistrationTransfer $lastRegistrationTransfer
    ): bool {
        return $quoteTransfer->getShippingAddress()->getIdCustomerAddress() !== $lastRegistrationTransfer->getIdCustomerAddress();
    }

    /**
     * @return \Generated\Shared\Transfer\HeidelpayPaymentOptionTransfer
     */
    protected function createPaymentOptionTransfer(): HeidelpayPaymentOptionTransfer
    {
        return (new HeidelpayPaymentOptionTransfer())
            ->setCode(HeidelpayConfig::PAYMENT_OPTION_EXISTING_REGISTRATION);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isRegisteredShippingAddressUsed(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShippingAddress() !== null
            && $quoteTransfer->getShippingAddress()->getIdCustomerAddress() !== null;
    }
}
