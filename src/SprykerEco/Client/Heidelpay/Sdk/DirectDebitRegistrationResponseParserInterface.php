<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay\Sdk;

use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer;

interface DirectDebitRegistrationResponseParserInterface
{
    /**
     * @param array $responseArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer
     */
    public function parseResponse(array $responseArray): HeidelpayDirectDebitRegistrationResponseTransfer;
}
