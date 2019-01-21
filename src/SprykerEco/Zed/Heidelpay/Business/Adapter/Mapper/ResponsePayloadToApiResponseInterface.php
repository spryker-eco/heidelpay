<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
