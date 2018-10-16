<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper;

use Generated\Shared\Transfer\HeidelpayBasketRequestTransfer;
use Heidelpay\PhpBasketApi\Object\Basket;

interface BasketRequestToHeidelpayInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayBasketRequestTransfer $requestTransfer
     * @param \Heidelpay\PhpBasketApi\Object\Basket $heidelpayBasket
     *
     * @return void
     */
    public function map(HeidelpayBasketRequestTransfer $requestTransfer, Basket $heidelpayBasket): void;
}
