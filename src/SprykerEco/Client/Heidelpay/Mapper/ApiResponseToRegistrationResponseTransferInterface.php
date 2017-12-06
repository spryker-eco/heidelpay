<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerEco\Client\Heidelpay\Mapper;

use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseErrorTransfer;
use Heidelpay\PhpApi\Response;

interface ApiResponseToRegistrationResponseTransferInterface
{
    /**
     * @param \Heidelpay\PhpApi\Response $apiResponseObject
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return void
     */
    public function map(Response $apiResponseObject, HeidelpayRegistrationRequestTransfer $registrationRequestTransfer);

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     * @param \Generated\Shared\Transfer\HeidelpayResponseErrorTransfer $errorTransfer
     *
     * @return void
     */
    public function hydrateErrorToRegistrationRequest(
        HeidelpayRegistrationRequestTransfer $registrationRequestTransfer,
        HeidelpayResponseErrorTransfer $errorTransfer
    );
}
