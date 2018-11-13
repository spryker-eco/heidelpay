<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper;

use Heidelpay\PhpPaymentApi\Response;

interface ResponsePayloadToApiResponseInterface
{
    /**
     * @param string $transactionPayload
     * @param \Heidelpay\PhpPaymentApi\Response $heidelpayResponse
     *
     * @return void
     */
    public function map($transactionPayload, Response $heidelpayResponse): void;
}
