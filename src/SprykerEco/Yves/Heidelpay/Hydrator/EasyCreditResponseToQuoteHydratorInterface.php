<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Hydrator;

use Generated\Shared\Transfer\QuoteTransfer;

interface EasyCreditResponseToQuoteHydratorInterface
{
    /**
     * @param array<string> $responseAsArray
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function hydrateQuoteTransferWithEasyCreditResponse(array $responseAsArray, QuoteTransfer $quoteTransfer): void;
}
