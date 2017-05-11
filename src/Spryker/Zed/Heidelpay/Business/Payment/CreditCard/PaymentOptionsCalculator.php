<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heidelpay\Business\Payment\CreditCard;

use Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class PaymentOptionsCalculator implements PaymentOptionsCalculatorInterface
{

    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface[]
     */
    protected $paymentOptions;

    /**
     * @param \Spryker\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption\PaymentOptionInterface[]
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
    public function getCreditCardPaymentOptions(QuoteTransfer $quoteTransfer)
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
