<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper;

use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Heidelpay\PhpApi\Response;

interface ResponseFromHeidelpayInterface
{

    /**
     * @param \Heidelpay\PhpApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function map(Response $apiResponse, HeidelpayResponseTransfer $responseTransfer);

}
