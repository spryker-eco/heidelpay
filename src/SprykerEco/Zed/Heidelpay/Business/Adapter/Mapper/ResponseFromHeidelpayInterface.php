<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper;

use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Heidelpay\PhpPaymentApi\Response;

interface ResponseFromHeidelpayInterface
{
    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function map(Response $apiResponse, HeidelpayResponseTransfer $responseTransfer): void;
}
