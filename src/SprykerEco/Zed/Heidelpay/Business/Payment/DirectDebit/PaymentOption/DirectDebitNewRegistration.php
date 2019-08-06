<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\PaymentOption;

use Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentOptionTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\DirectDebitPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderInterface;

class DirectDebitNewRegistration implements DirectDebitPaymentOptionInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderInterface
     */
    protected $adapterRequestBuilder;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\DirectDebitPaymentInterface
     */
    protected $directDebitPayment;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderInterface $adapterRequestBuilder
     * @param \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\DirectDebitPaymentInterface $directDebitPayment
     */
    public function __construct(
        AdapterRequestFromQuoteBuilderInterface $adapterRequestBuilder,
        DirectDebitPaymentInterface $directDebitPayment
    ) {
        $this->adapterRequestBuilder = $adapterRequestBuilder;
        $this->directDebitPayment = $directDebitPayment;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer $paymentOptionsTransfer
     *
     * @return void
     */
    public function hydrateToPaymentOptions(
        QuoteTransfer $quoteTransfer,
        HeidelpayDirectDebitPaymentOptionsTransfer $paymentOptionsTransfer
    ): void {
        $registrationResponseTransfer = $this->registerQuote($quoteTransfer);
        $this->mapResponseToPaymentOptions($paymentOptionsTransfer, $registrationResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isOptionAvailableForQuote(QuoteTransfer $quoteTransfer): bool
    {
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    protected function registerQuote(QuoteTransfer $quoteTransfer): HeidelpayResponseTransfer
    {
        $registrationRequestTransfer = $this->adapterRequestBuilder->buildDirectDebitRegistrationRequest($quoteTransfer);
        $registrationResponseTransfer = $this->directDebitPayment->register($registrationRequestTransfer);

        return $registrationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer $paymentOptionsTransfer
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $registrationResponseTransfer
     *
     * @return void
     */
    protected function mapResponseToPaymentOptions(
        HeidelpayDirectDebitPaymentOptionsTransfer $paymentOptionsTransfer,
        HeidelpayResponseTransfer $registrationResponseTransfer
    ): void {
        if ($registrationResponseTransfer->getIsError()) {
            return;
        }

        $this->addNewRegistrationAsPaymentOption($paymentOptionsTransfer);

        $paymentOptionsTransfer
            ->setPaymentFormActionUrl($registrationResponseTransfer->getPaymentFormUrl());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer $paymentOptionsTransfer
     *
     * @return void
     */
    protected function addNewRegistrationAsPaymentOption(HeidelpayDirectDebitPaymentOptionsTransfer $paymentOptionsTransfer): void
    {
        $optionsList = $paymentOptionsTransfer->getOptionsList();

        $optionsList[] = (new HeidelpayPaymentOptionTransfer())
            ->setCode(HeidelpayConfig::PAYMENT_OPTION_NEW_REGISTRATION);

        $paymentOptionsTransfer->setOptionsList($optionsList);
    }
}
