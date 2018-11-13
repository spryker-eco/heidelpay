<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Basket;

use Generated\Shared\Transfer\HeidelpayBasketRequestTransfer;
use Generated\Shared\Transfer\HeidelpayBasketResponseTransfer;

interface BasketInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayBasketRequestTransfer $heidelpayBasketRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayBasketResponseTransfer
     */
    public function addNewBasket(HeidelpayBasketRequestTransfer $heidelpayBasketRequestTransfer): HeidelpayBasketResponseTransfer;
}
