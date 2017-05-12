<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
    public function saveRegistrationFromQuote(QuoteTransfer $quoteTransfer);

}
