<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Basket;

use Generated\Shared\Transfer\HeidelpayBasketResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface BasketCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayBasketResponseTransfer
     */
    public function createBasket(QuoteTransfer $quoteTransfer): HeidelpayBasketResponseTransfer;
}
