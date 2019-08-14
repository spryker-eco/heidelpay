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
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer
     */
    public function addPaymentOption(
        QuoteTransfer $quoteTransfer,
        HeidelpayDirectDebitPaymentOptionsTransfer $paymentOptionsTransfer
    ): HeidelpayDirectDebitPaymentOptionsTransfer {
        $registrationResponseTransfer = $this->registerQuote($quoteTransfer);

        if ($registrationResponseTransfer->getIsError()) {
            return $paymentOptionsTransfer;
        }

        $paymentOptionsTransfer
            ->addOption($this->createPaymentOptionTransfer())
            ->setPaymentFormActionUrl($registrationResponseTransfer->getPaymentFormUrl());

        return $paymentOptionsTransfer;
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
     * @return \Generated\Shared\Transfer\HeidelpayPaymentOptionTransfer
     */
    protected function createPaymentOptionTransfer(): HeidelpayPaymentOptionTransfer
    {
        return (new HeidelpayPaymentOptionTransfer())
            ->setCode(HeidelpayConfig::DIRECT_DEBIT_PAYMENT_OPTION_NEW_REGISTRATION);
    }
}
