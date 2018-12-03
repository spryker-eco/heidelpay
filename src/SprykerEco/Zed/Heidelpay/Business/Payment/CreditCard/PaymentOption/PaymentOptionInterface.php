<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\PaymentOption;

use Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PaymentOptionInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer $paymentOptionsTransfer
     *
     * @return void
     */
    public function hydrateToPaymentOptions(
        QuoteTransfer $quoteTransfer,
        HeidelpayCreditCardPaymentOptionsTransfer $paymentOptionsTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isOptionAvailableForQuote(QuoteTransfer $quoteTransfer): bool;
}
