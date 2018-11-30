<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Shared\Heidelpay;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteUniqueIdGenerator
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public static function getHashByCustomerEmailAndTotals(QuoteTransfer $quoteTransfer): string
    {
        $quoteTransfer
            ->requireCustomer()
            ->getTotals();

        return sha1($quoteTransfer->getCustomer()->getEmail() . $quoteTransfer->getTotals()->getHash());
    }
}
