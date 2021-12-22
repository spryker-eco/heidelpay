<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay\Mapper;

use Generated\Shared\Transfer\HeidelpayCreditCardInfoTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseErrorTransfer;
use Heidelpay\PhpPaymentApi\Response;

class ApiResponseToRegistrationRequestTransfer implements ApiResponseToRegistrationResponseTransferInterface
{
    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponseObject
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return void
     */
    public function map(Response $apiResponseObject, HeidelpayRegistrationRequestTransfer $registrationRequestTransfer): void
    {
        $this->mapCreditCardInfo($apiResponseObject, $registrationRequestTransfer);
        $this->mapError($apiResponseObject, $registrationRequestTransfer);

        $registrationRequestTransfer
            ->setRegistrationHash(
                $apiResponseObject->getIdentification()->getUniqueId(),
            )
            ->setQuoteHash(
                $apiResponseObject->getIdentification()->getTransactionId(),
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
    ): void {
        $registrationRequestTransfer
            ->setIsError(true)
            ->setError($errorTransfer);
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $responseTransfer
     *
     * @return void
     */
    protected function mapError(Response $apiResponse, HeidelpayRegistrationRequestTransfer $responseTransfer): void
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
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponseObject
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return void
     */
    protected function mapCreditCardInfo(Response $apiResponseObject, HeidelpayRegistrationRequestTransfer $registrationRequestTransfer): void
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
