<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Heidelpay\Sdk;

interface CreditCardRegistrationResponseParserInterface
{

    /**
     * @param array $responseArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer
     */
    public function parseExternalResponse(array $responseArray);

}
