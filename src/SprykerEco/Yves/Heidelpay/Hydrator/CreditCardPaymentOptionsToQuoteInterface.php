<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Hydrator;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface CreditCardPaymentOptionsToQuoteInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function hydrate(AbstractTransfer $quoteTransfer);
}
