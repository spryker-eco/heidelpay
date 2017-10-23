<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper;

use Heidelpay\PhpApi\Response;

interface ResponsePayloadToApiResponseInterface
{
    /**
     * @param string $transactionPayload
     * @param \Heidelpay\PhpApi\Response $heidelpayResponse
     *
     * @return void
     */
    public function map($transactionPayload, Response $heidelpayResponse);
}
