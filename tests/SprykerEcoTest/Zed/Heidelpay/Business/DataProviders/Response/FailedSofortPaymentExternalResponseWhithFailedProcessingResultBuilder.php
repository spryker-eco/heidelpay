<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response;


class FailedSofortPaymentExternalResponseWhithFailedProcessingResultBuilder extends ResponseBuilder
{
    /**
     * @return string
     */
    protected function getProcessingResult()
    {
        return null;
    }
}