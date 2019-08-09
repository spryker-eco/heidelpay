<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Form\DataProvider;

use Generated\Shared\Transfer\HeidelpayDirectDebitPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Yves\Heidelpay\Form\DirectDebitSubForm;

class DirectDebitDataProvider implements StepEngineFormDataProviderInterface
{
    protected const PAYMENT_OPTION_NAME_TRANSLATION_PATTERN = 'heidelpay.payment.direct_debit.%s';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getPayment() === null) {
            $quoteTransfer->setPayment(new PaymentTransfer());
        }

        if ($quoteTransfer->getPayment()->getHeidelpayDirectDebit() !== null) {
            return $quoteTransfer;
        }

        $quoteTransfer->getPayment()->setHeidelpayDirectDebit(new HeidelpayDirectDebitPaymentTransfer());

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        $directDebitPayment = $this->selectPaymentOption(
            $quoteTransfer->getPayment()->getHeidelpayDirectDebit()
        );

        $quoteTransfer->getPayment()->setHeidelpayDirectDebit($directDebitPayment);

        return [
            DirectDebitSubForm::PAYMENT_OPTIONS => $this->fetchPaymentOptions($directDebitPayment),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentTransfer $directDebitPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentTransfer
     */
    protected function selectPaymentOption(HeidelpayDirectDebitPaymentTransfer $directDebitPaymentTransfer): HeidelpayDirectDebitPaymentTransfer
    {
        $directDebitPaymentTransfer = $this->unsetNotAvailableSelection($directDebitPaymentTransfer);

        if ($directDebitPaymentTransfer->getSelectedPaymentOption() !== null) {
            return $directDebitPaymentTransfer;
        }

        $directDebitPaymentTransfer = $this->setDefaultPaymentOption($directDebitPaymentTransfer);

        return $directDebitPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentTransfer $directDebitPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentTransfer
     */
    protected function unsetNotAvailableSelection(HeidelpayDirectDebitPaymentTransfer $directDebitPaymentTransfer): HeidelpayDirectDebitPaymentTransfer
    {
        $selectedPaymentOption = $directDebitPaymentTransfer->getSelectedPaymentOption();

        if ($selectedPaymentOption === null) {
            return $directDebitPaymentTransfer;
        }

        $availableOptions = $this->fetchPaymentOptions($directDebitPaymentTransfer);

        if (!isset($availableOptions[$selectedPaymentOption])) {
            $directDebitPaymentTransfer->setSelectedPaymentOption(null);
        }

        return $directDebitPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentTransfer $directDebitPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentTransfer
     */
    protected function setDefaultPaymentOption(HeidelpayDirectDebitPaymentTransfer $directDebitPaymentTransfer): HeidelpayDirectDebitPaymentTransfer
    {
        $options = $this->fetchPaymentOptions($directDebitPaymentTransfer);
        $defaultOption = array_shift($options);
        $directDebitPaymentTransfer->setSelectedPaymentOption($defaultOption);

        return $directDebitPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentTransfer $directDebitPaymentTransfer
     *
     * @return string[]
     */
    protected function fetchPaymentOptions(HeidelpayDirectDebitPaymentTransfer $directDebitPaymentTransfer): array
    {
        $paymentOptions = [];

        $paymentOptionsList = $directDebitPaymentTransfer
            ->getPaymentOptions()
            ->getOptionsList();

        foreach ($paymentOptionsList as $optionTransfer) {
            $paymentOptions[$optionTransfer->getCode()] = sprintf(static::PAYMENT_OPTION_NAME_TRANSLATION_PATTERN, $optionTransfer->getCode());
        }

        return $paymentOptions;
    }
}
