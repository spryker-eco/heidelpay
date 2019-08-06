<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay\Mapper;

use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer;
use Heidelpay\PhpPaymentApi\Response;

interface DirectDebitRegistrationResponseMapperInterface
{
    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponseObject
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer $registrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer
     */
    public function mapApiResponseToDirectDebitRegistrationResponseTransfer(
        Response $apiResponseObject,
        HeidelpayDirectDebitRegistrationResponseTransfer $registrationRequestTransfer
    ): HeidelpayDirectDebitRegistrationResponseTransfer;
}
