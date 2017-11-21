<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response;


class FailedSofortPaymentExternalResponseWhithIncorrectHashBuilder extends ResponseBuilder
{
    /**
     * @param $identificationTransactionId
     * @param $secret
     * @return string
     */
    protected function getCriterionSecret($identificationTransactionId, $secret)
    {
        return 'failed-secret-value';
    }
}