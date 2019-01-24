<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse;

use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponseBuilder;

class FailedEasyCreditPaymentExternalResponseWhithIncorrectHashBuilder extends ExternalResponseBuilder
{
    /**
     * @param int $identificationTransactionId
     * @param string $secret
     *
     * @return string
     */
    protected function getCriterionSecret(int $identificationTransactionId, string $secret): string
    {
        return 'failed-secret-value';
    }
}
