<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit;

use Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class DirectDebitPaymentOptionsCalculator implements DirectDebitPaymentOptionsCalculatorInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface[]
     */
    protected $paymentOptions;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\PaymentOption\DirectDebitPaymentOptionInterface[] $paymentOptions
     */
    public function __construct(array $paymentOptions)
    {
        $this->paymentOptions = $paymentOptions;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer
     */
    public function getDirectDebitPaymentOptions(QuoteTransfer $quoteTransfer): HeidelpayDirectDebitPaymentOptionsTransfer
    {
        $paymentOptionsTransfer = new HeidelpayDirectDebitPaymentOptionsTransfer();

        foreach ($this->paymentOptions as $paymentOption) {
            if (!$paymentOption->isOptionAvailableForQuote($quoteTransfer)) {
                continue;
            }
            $paymentOption->hydrateToPaymentOptions($quoteTransfer, $paymentOptionsTransfer);
        }

        return $paymentOptionsTransfer;
    }
}
