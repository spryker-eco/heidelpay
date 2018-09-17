<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse;

use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponseBuilder;

class FailedEasyCreditPaymentExternalResponseWhithFailedProcessingResultBuilder extends ExternalResponseBuilder
{
    /**
     * @return string|null
     */
    protected function getProcessingResult()
    {
        return null;
    }
}
