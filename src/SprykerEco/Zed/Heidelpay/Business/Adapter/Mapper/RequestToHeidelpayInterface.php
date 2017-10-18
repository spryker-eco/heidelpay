<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Heidelpay\PhpApi\Request;

interface RequestToHeidelpayInterface
{

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $requestTransfer
     * @param \Heidelpay\PhpApi\Request $heidelpayRequest
     *
     * @return void
     */
    public function map(HeidelpayRequestTransfer $requestTransfer, Request $heidelpayRequest);

}
