<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Client\Heidelpay\Mapper;

use Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer;
use Generated\Shared\Transfer\HeidelpayResponseErrorTransfer;
use Heidelpay\PhpApi\Response;

interface ApiResponseToRegistrationResponseTransferInterface
{

    /**
     * @param \Heidelpay\PhpApi\Response $apiResponseObject
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer $registrationResponseTransfer
     *
     * @return void
     */
    public function map(Response $apiResponseObject, HeidelpayRegistrationResponseTransfer $registrationResponseTransfer);

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer $registrationResponseTransfer
     * @param \Generated\Shared\Transfer\HeidelpayResponseErrorTransfer $errorTransfer
     *
     * @return void
     */
    public function hydrateErrorToRegistrationResponse(
        HeidelpayRegistrationResponseTransfer $registrationResponseTransfer,
        HeidelpayResponseErrorTransfer $errorTransfer
    );

}
