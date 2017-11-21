<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response;


class FailedSofortPaymentExternalResponseWhithIncorrectTransactionIdBuilder extends ResponseBuilder
{
    /**
     * @param $orderEntity
     * @return integer
     */
    protected function getTransationId($orderEntity)
    {
        return 100000000000;
    }
}