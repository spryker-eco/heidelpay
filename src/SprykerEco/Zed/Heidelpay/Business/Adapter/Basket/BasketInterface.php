<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
