<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse;

use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponseBuilder;

class FailedSofortPaymentExternalResponseWhithFailedProcessingResultBuilder extends ExternalResponseBuilder
{
    /**
     * @return string|null
     */
    protected function getProcessingResult(): ?string
    {
        return null;
    }
}
