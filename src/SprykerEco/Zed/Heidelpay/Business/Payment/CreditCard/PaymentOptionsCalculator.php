<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard;

use Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class PaymentOptionsCalculator implements PaymentOptionsCalculatorInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface[]
     */
    protected $paymentOptions;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface[] $paymentOptions
     */
    public function __construct(array $paymentOptions)
    {
        $this->paymentOptions = $paymentOptions;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer
     */
    public function getCreditCardPaymentOptions(QuoteTransfer $quoteTransfer): HeidelpayCreditCardPaymentOptionsTransfer
    {
        $paymentOptionsTransfer = new HeidelpayCreditCardPaymentOptionsTransfer();

        foreach ($this->paymentOptions as $paymentOption) {
            if (!$paymentOption->isOptionAvailableForQuote($quoteTransfer)) {
                continue;
            }
            $paymentOption->hydrateToPaymentOptions($quoteTransfer, $paymentOptionsTransfer);
        }

        return $paymentOptionsTransfer;
    }
}
