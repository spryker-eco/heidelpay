<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay\Sdk;

use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;

interface CreditCardRegistrationResponseParserInterface
{
    /**
     * @param array<string> $responseArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer
     */
    public function parseExternalResponse(array $responseArray): HeidelpayRegistrationRequestTransfer;
}
