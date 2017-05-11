<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Client\Heidelpay\Mapper;

use Generated\Shared\Transfer\HeidelpayCreditCardInfoTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer;
use Generated\Shared\Transfer\HeidelpayResponseErrorTransfer;
use Heidelpay\PhpApi\Response;

class ApiResponseToRegistrationResponseTransfer implements ApiResponseToRegistrationResponseTransferInterface
{

    /**
     * @param \Heidelpay\PhpApi\Response $apiResponseObject
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer $registrationResponseTransfer
     *
     * @return void
     */
    public function map(Response $apiResponseObject, HeidelpayRegistrationResponseTransfer $registrationResponseTransfer)
    {
        $this->mapCreditCardInfo($apiResponseObject, $registrationResponseTransfer);
        $this->mapError($apiResponseObject, $registrationResponseTransfer);

        $registrationResponseTransfer->setIdRegistration(
            $apiResponseObject->getIdentification()->getUniqueId()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer $registrationResponseTransfer
     * @param \Generated\Shared\Transfer\HeidelpayResponseErrorTransfer $errorTransfer
     *
     * @return void
     */
    public function hydrateErrorToRegistrationResponse(
        HeidelpayRegistrationResponseTransfer $registrationResponseTransfer,
        HeidelpayResponseErrorTransfer $errorTransfer
    ) {
        $registrationResponseTransfer
            ->setIsError(true)
            ->setError($errorTransfer);
    }

    /**
     * @param \Heidelpay\PhpApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function mapError(Response $apiResponse, HeidelpayRegistrationResponseTransfer $responseTransfer)
    {
        if (!$apiResponse->isError()) {
            return;
        }

        $errorResponse = $apiResponse->getError();
        $errorTransfer = (new HeidelpayResponseErrorTransfer())
            ->setCode($errorResponse['code'])
            ->setInternalMessage($errorResponse['message']);

        $this->hydrateErrorToRegistrationResponse($responseTransfer, $errorTransfer);
    }

    /**
     * @param \Heidelpay\PhpApi\Response $apiResponseObject
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer $registrationResponseTransfer
     *
     * @return void
     */
    protected function mapCreditCardInfo(Response $apiResponseObject, HeidelpayRegistrationResponseTransfer $registrationResponseTransfer)
    {
        $creditCardInfoTransfer = new HeidelpayCreditCardInfoTransfer();
        $accountInfo = $apiResponseObject->getAccount();

        $creditCardInfoTransfer
            ->setAccountBrand($accountInfo->getBrand())
            ->setAccountExpiryMonth($accountInfo->getExpiryMonth())
            ->setAccountExpiryYear($accountInfo->getExpiryYear())
            ->setAccountHolder($accountInfo->getHolder())
            ->setAccountNumber($accountInfo->getNumber())
            ->setAccountVerification($accountInfo->getVerification());

        $registrationResponseTransfer->setCreditCardInfo($creditCardInfoTransfer);
    }

}
