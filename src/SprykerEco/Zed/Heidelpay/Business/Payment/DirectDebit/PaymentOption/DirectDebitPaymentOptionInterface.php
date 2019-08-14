<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\PaymentOption;

use Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface DirectDebitPaymentOptionInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer $paymentOptionsTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer
     */
    public function addPaymentOption(
        QuoteTransfer $quoteTransfer,
        HeidelpayDirectDebitPaymentOptionsTransfer $paymentOptionsTransfer
    ): HeidelpayDirectDebitPaymentOptionsTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isOptionAvailableForQuote(QuoteTransfer $quoteTransfer): bool;
}
