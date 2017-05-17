<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Heidelpay;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteUniqueIdGenerator
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return mixed
     */
    public static function getHashByCustomerEmailAndTotals(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer
            ->requireCustomer()
            ->getTotals();

        return sha1($quoteTransfer->getCustomer()->getEmail() . $quoteTransfer->getTotals()->getHash());
    }

}
