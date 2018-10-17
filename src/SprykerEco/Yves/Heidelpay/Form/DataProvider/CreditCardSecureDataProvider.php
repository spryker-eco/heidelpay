<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Form\DataProvider;

use Generated\Shared\Transfer\HeidelpayCreditCardPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Yves\Heidelpay\Form\CreditCardSecureSubForm;
use SprykerEco\Yves\Heidelpay\Hydrator\CreditCardPaymentOptionsToQuoteInterface;

class CreditCardSecureDataProvider implements StepEngineFormDataProviderInterface
{
    const PAYMENT_OPTION_TRANSLATION_PREFIX = 'heidelpay.payment.credit_card.';
    /**
     * @var \SprykerEco\Yves\Heidelpay\Hydrator\CreditCardPaymentOptionsToQuoteInterface
     */
    protected $paymentOptionsHydrator;

    /**
     * @param \SprykerEco\Yves\Heidelpay\Hydrator\CreditCardPaymentOptionsToQuoteInterface $paymentOptionsHydrator
     */
    public function __construct(
        CreditCardPaymentOptionsToQuoteInterface $paymentOptionsHydrator
    ) {
        $this->paymentOptionsHydrator = $paymentOptionsHydrator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        $this->initPaymentObject($quoteTransfer);
        $this->hydratePaymentOptionsToQuote($quoteTransfer);

        $quoteTransfer->getPayment()->requireHeidelpayCreditCardSecure();

        $creditCardPayment = $quoteTransfer->getPayment()->getHeidelpayCreditCardSecure();
        $this->selectPaymentOption($creditCardPayment);

        return [
            CreditCardSecureSubForm::PAYMENT_OPTIONS => $this->fetchPaymentOptions($creditCardPayment),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function initPaymentObject(AbstractTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPayment() === null) {
            $creditCardPaymentTransfer = (new PaymentTransfer())
                ->setPaymentMethod(HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE);

            $quoteTransfer->setPayment($creditCardPaymentTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function hydratePaymentOptionsToQuote(AbstractTransfer $quoteTransfer)
    {
        $quoteTransfer->requirePayment();
        $this->paymentOptionsHydrator->hydrate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayCreditCardPaymentTransfer $creditCardPaymentTransfer
     *
     * @return void
     */
    protected function selectPaymentOption(HeidelpayCreditCardPaymentTransfer $creditCardPaymentTransfer)
    {
        $this->unsetNotAvailableSelection($creditCardPaymentTransfer);

        if ($creditCardPaymentTransfer->getSelectedPaymentOption() !== null) {
            return;
        }

        $this->setDefaultPaymentOption($creditCardPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayCreditCardPaymentTransfer $creditCardPaymentTransfer
     *
     * @return void
     */
    protected function unsetNotAvailableSelection(HeidelpayCreditCardPaymentTransfer $creditCardPaymentTransfer)
    {
        $selectedPaymentOption = $creditCardPaymentTransfer->getSelectedPaymentOption();

        if ($selectedPaymentOption === null) {
            return;
        }

        $availableOptions = $this->fetchPaymentOptions($creditCardPaymentTransfer);

        if (!isset($availableOptions[$selectedPaymentOption])) {
            $creditCardPaymentTransfer->setSelectedPaymentOption(null);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayCreditCardPaymentTransfer $creditCardPaymentTransfer
     *
     * @return void
     */
    protected function setDefaultPaymentOption(HeidelpayCreditCardPaymentTransfer $creditCardPaymentTransfer)
    {
        $availableOptions = array_keys($this->fetchPaymentOptions($creditCardPaymentTransfer));
        $creditCardPaymentTransfer->setSelectedPaymentOption(array_shift($availableOptions));
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayCreditCardPaymentTransfer $creditCardPaymentTransfer
     *
     * @return array
     */
    protected function fetchPaymentOptions(HeidelpayCreditCardPaymentTransfer $creditCardPaymentTransfer)
    {
        $paymentOptions = [];

        $paymentOptionsList = $creditCardPaymentTransfer
            ->getPaymentOptions()
            ->getOptionsList();

        foreach ($paymentOptionsList as $optionTransfer) {
            $paymentOptions[static::PAYMENT_OPTION_TRANSLATION_PREFIX . $optionTransfer->getCode()] =
                $optionTransfer->getCode();
        }

        return $paymentOptions;
    }
}
