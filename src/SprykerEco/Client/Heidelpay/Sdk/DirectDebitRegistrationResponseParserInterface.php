<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay\Sdk;

use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;

interface DirectDebitRegistrationResponseParserInterface
{
    /**
     * @param array<string> $responseArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function parseResponse(array $responseArray): HeidelpayDirectDebitRegistrationTransfer;
}
