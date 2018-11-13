<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper;

use Generated\Shared\Transfer\HeidelpayBasketResponseTransfer;
use Heidelpay\PhpBasketApi\Response;

class BasketResponseFromHeidelpay implements BasketResponseFromHeidelpayInterface
{
    /**
     * @param \Heidelpay\PhpBasketApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayBasketResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function map(Response $apiResponse, HeidelpayBasketResponseTransfer $responseTransfer): void
    {
        $responseTransfer->setIdBasket($apiResponse->getBasketId());
    }
}
