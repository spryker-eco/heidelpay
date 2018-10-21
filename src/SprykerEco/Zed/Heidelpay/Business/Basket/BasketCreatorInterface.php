<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
