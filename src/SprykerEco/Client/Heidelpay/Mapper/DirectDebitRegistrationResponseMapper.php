<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay\Mapper;

use Generated\Shared\Transfer\HeidelpayDirectDebitAccountTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer;
use Generated\Shared\Transfer\HeidelpayResponseErrorTransfer;
use Heidelpay\PhpPaymentApi\Response;

class DirectDebitRegistrationResponseMapper implements DirectDebitRegistrationResponseMapperInterface
{
    protected const API_RESPONSE_ERROR_CODE_KEY = 'code';
    protected const API_RESPONSE_ERROR_MESSAGE_KEY = 'message';

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponseObject
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer $registrationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer
     */
    public function mapApiResponseToDirectDebitRegistrationResponseTransfer(
        Response $apiResponseObject,
        HeidelpayDirectDebitRegistrationResponseTransfer $registrationResponseTransfer
    ): HeidelpayDirectDebitRegistrationResponseTransfer {
        $registrationResponseTransfer = $this->addAccountInfo($apiResponseObject, $registrationResponseTransfer);
        $registrationResponseTransfer = $this->addError($apiResponseObject, $registrationResponseTransfer);
        $registrationResponseTransfer = $this->addIdentification($apiResponseObject, $registrationResponseTransfer);

        return $registrationResponseTransfer;
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponseObject
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer $registrationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer
     */
    protected function addAccountInfo(
        Response $apiResponseObject,
        HeidelpayDirectDebitRegistrationResponseTransfer $registrationResponseTransfer
    ): HeidelpayDirectDebitRegistrationResponseTransfer {
        $accountInfo = $apiResponseObject->getAccount();
        $directDebitAccountTransfer = (new HeidelpayDirectDebitAccountTransfer())
            ->setAccountHolder($accountInfo->getHolder())
            ->setAccountBankName($accountInfo->getBankName())
            ->setAccountNumber($accountInfo->getNumber())
            ->setAccountCountry($accountInfo->getCountry())
            ->setAccountBic($accountInfo->getBic())
            ->setAccountIban($accountInfo->getIban())
            ->setAccountIdentification($accountInfo->getIdentification());

        $registrationResponseTransfer->setAccountInfo($directDebitAccountTransfer);

        return $registrationResponseTransfer;
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer $registrationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer
     */
    protected function addError(
        Response $apiResponse,
        HeidelpayDirectDebitRegistrationResponseTransfer $registrationResponseTransfer
    ): HeidelpayDirectDebitRegistrationResponseTransfer {
        if (!$apiResponse->isError()) {
            return $registrationResponseTransfer;
        }

        $errorResponse = $apiResponse->getError();
        $registrationResponseTransfer
            ->setIsError(true)
            ->setError(
                (new HeidelpayResponseErrorTransfer())
                    ->setCode($errorResponse[static::API_RESPONSE_ERROR_CODE_KEY])
                    ->setInternalMessage($errorResponse[static::API_RESPONSE_ERROR_MESSAGE_KEY])
            );

        return $registrationResponseTransfer;
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer $registrationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationResponseTransfer
     */
    protected function addIdentification(
        Response $apiResponse,
        HeidelpayDirectDebitRegistrationResponseTransfer $registrationResponseTransfer
    ): HeidelpayDirectDebitRegistrationResponseTransfer {
        $registrationResponseTransfer
            ->setRegistrationUniqueId($apiResponse->getIdentification()->getUniqueId())
            ->setTransactionId($apiResponse->getIdentification()->getTransactionId());

        return $registrationResponseTransfer;
    }
}
