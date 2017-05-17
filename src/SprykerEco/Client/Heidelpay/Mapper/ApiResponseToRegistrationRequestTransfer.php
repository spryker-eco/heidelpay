<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerEco\Client\Heidelpay\Mapper;

use Generated\Shared\Transfer\HeidelpayCreditCardInfoTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseErrorTransfer;
use Heidelpay\PhpApi\Response;

class ApiResponseToRegistrationRequestTransfer implements ApiResponseToRegistrationResponseTransferInterface
{

    /**
     * @param \Heidelpay\PhpApi\Response $apiResponseObject
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return void
     */
    public function map(Response $apiResponseObject, HeidelpayRegistrationRequestTransfer $registrationRequestTransfer)
    {
        $this->mapCreditCardInfo($apiResponseObject, $registrationRequestTransfer);
        $this->mapError($apiResponseObject, $registrationRequestTransfer);

        $registrationRequestTransfer
            ->setRegistrationHash(
                $apiResponseObject->getIdentification()->getUniqueId()
            )
            ->setQuoteHash(
                $apiResponseObject->getIdentification()->getTransactionId()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     * @param \Generated\Shared\Transfer\HeidelpayResponseErrorTransfer $errorTransfer
     *
     * @return void
     */
    public function hydrateErrorToRegistrationRequest(
        HeidelpayRegistrationRequestTransfer $registrationRequestTransfer,
        HeidelpayResponseErrorTransfer $errorTransfer
    ) {
        $registrationRequestTransfer
            ->setIsError(true)
            ->setError($errorTransfer);
    }

    /**
     * @param \Heidelpay\PhpApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $responseTransfer
     *
     * @return void
     */
    protected function mapError(Response $apiResponse, HeidelpayRegistrationRequestTransfer $responseTransfer)
    {
        if (!$apiResponse->isError()) {
            return;
        }

        $errorResponse = $apiResponse->getError();
        $errorTransfer = (new HeidelpayResponseErrorTransfer())
            ->setCode($errorResponse['code'])
            ->setInternalMessage($errorResponse['message']);

        $this->hydrateErrorToRegistrationRequest($responseTransfer, $errorTransfer);
    }

    /**
     * @param \Heidelpay\PhpApi\Response $apiResponseObject
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return void
     */
    protected function mapCreditCardInfo(Response $apiResponseObject, HeidelpayRegistrationRequestTransfer $registrationRequestTransfer)
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

        $registrationRequestTransfer->setCreditCardInfo($creditCardInfoTransfer);
    }

}
