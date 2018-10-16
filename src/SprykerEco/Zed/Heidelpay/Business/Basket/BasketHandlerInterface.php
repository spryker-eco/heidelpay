<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Basket;

use Generated\Shared\Transfer\QuoteTransfer;

interface BasketHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Heidelpay\PhpBasketApi\Response
     */
    public function createBasket(QuoteTransfer $quoteTransfer);
}
