<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration;

use Generated\Shared\Transfer\QuoteTransfer;

interface RegistrationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function updateRegistrationWithAddressIdFromQuote(QuoteTransfer $quoteTransfer): void;
}
