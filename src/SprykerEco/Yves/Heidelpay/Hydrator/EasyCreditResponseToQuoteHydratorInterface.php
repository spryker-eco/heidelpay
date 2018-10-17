<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Hydrator;

use Generated\Shared\Transfer\QuoteTransfer;

interface EasyCreditResponseToQuoteHydratorInterface
{
    /**
     * @param array $responseAsArray
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function hydrateEasyCreditResponseToQuote(
        array $responseAsArray,
        QuoteTransfer $quoteTransfer
    );
}
