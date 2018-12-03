<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper;

use Generated\Shared\Transfer\HeidelpayBasketResponseTransfer;
use Heidelpay\PhpBasketApi\Response;

interface BasketResponseFromHeidelpayInterface
{
    /**
     * @param \Heidelpay\PhpBasketApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayBasketResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function map(Response $apiResponse, HeidelpayBasketResponseTransfer $responseTransfer): void;
}
