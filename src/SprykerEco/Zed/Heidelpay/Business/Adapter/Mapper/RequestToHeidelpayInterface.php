<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Heidelpay\PhpPaymentApi\Request;

interface RequestToHeidelpayInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $requestTransfer
     * @param \Heidelpay\PhpPaymentApi\Request $heidelpayRequest
     *
     * @return void
     */
    public function map(HeidelpayRequestTransfer $requestTransfer, Request $heidelpayRequest): void;
}
