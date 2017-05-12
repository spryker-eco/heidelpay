<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption;

use Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentOptionTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderInterface;

class NewRegistrationIframe implements PaymentOptionInterface
{

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderInterface
     */
    protected $adapterRequestBuilder;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface
     */
    protected $creditCardPayment;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderInterface $adapterRequestBuilder
     * @param \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface $creditCardPayment
     */
    public function __construct(
        AdapterRequestFromQuoteBuilderInterface $adapterRequestBuilder,
        CreditCardPaymentInterface $creditCardPayment
    ) {
        $this->adapterRequestBuilder = $adapterRequestBuilder;
        $this->creditCardPayment = $creditCardPayment;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer $paymentOptionsTransfer
     *
     * @return void
     */
    public function hydrateToPaymentOptions(
        QuoteTransfer $quoteTransfer,
        HeidelpayCreditCardPaymentOptionsTransfer $paymentOptionsTransfer
    ) {
        $registrationResponseTransfer = $this->registerQuote($quoteTransfer);
        $this->mapResponseToPaymentOptions($paymentOptionsTransfer, $registrationResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isOptionAvailableForQuote(QuoteTransfer $quoteTransfer)
    {
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    protected function registerQuote(QuoteTransfer $quoteTransfer)
    {
        $registrationRequestTransfer = $this->adapterRequestBuilder->buildCreditCardRegistrationRequest($quoteTransfer);
        $registrationResponseTransfer = $this->creditCardPayment->register($registrationRequestTransfer);

        return $registrationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer $paymentOptionsTransfer
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $registrationResponseTransfer
     *
     * @return void
     */
    protected function mapResponseToPaymentOptions(
        HeidelpayCreditCardPaymentOptionsTransfer $paymentOptionsTransfer,
        HeidelpayResponseTransfer $registrationResponseTransfer
    ) {
        if ($registrationResponseTransfer->getIsError()) {
            return;
        }

        $this->addNewRegistrationAsPaymentOption($paymentOptionsTransfer);

        $paymentOptionsTransfer
            ->setPaymentFrameUrl($registrationResponseTransfer->getPaymentFormUrl());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer $paymentOptionsTransfer
     *
     * @return void
     */
    protected function addNewRegistrationAsPaymentOption(HeidelpayCreditCardPaymentOptionsTransfer $paymentOptionsTransfer)
    {
        $optionsList = $paymentOptionsTransfer->getOptionsList();

        $optionsList[] = (new HeidelpayPaymentOptionTransfer())
            ->setCode(HeidelpayConstants::PAYMENT_OPTION_NEW_REGISTRATION);

        $paymentOptionsTransfer->setOptionsList($optionsList);
    }

}
