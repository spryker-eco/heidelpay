<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse;


use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponseBuilder;

class FailedSofortPaymentExternalResponseWhithIncorrectTransactionIdBuilder extends ExternalResponseBuilder
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