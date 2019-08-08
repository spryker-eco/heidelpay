<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay\Mapper;

use Generated\Shared\Transfer\HeidelpayDirectDebitAccountTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayResponseErrorTransfer;
use Heidelpay\PhpPaymentApi\Response;

class DirectDebitRegistrationResponseMapper implements DirectDebitRegistrationResponseMapperInterface
{
    protected const API_RESPONSE_ERROR_CODE_KEY = 'code';
    protected const API_RESPONSE_ERROR_MESSAGE_KEY = 'message';

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponseObject
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function mapApiResponseToDirectDebitRegistrationResponseTransfer(
        Response $apiResponseObject,
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        $directDebitRegistrationTransfer = $this->addAccountInfo($apiResponseObject, $directDebitRegistrationTransfer);
        $directDebitRegistrationTransfer = $this->addError($apiResponseObject, $directDebitRegistrationTransfer);
        $directDebitRegistrationTransfer = $this->addIdentification($apiResponseObject, $directDebitRegistrationTransfer);

        return $directDebitRegistrationTransfer;
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponseObject
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    protected function addAccountInfo(
        Response $apiResponseObject,
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        $accountInfo = $apiResponseObject->getAccount();
        $directDebitAccountTransfer = (new HeidelpayDirectDebitAccountTransfer())
            ->setAccountHolder($accountInfo->getHolder())
            ->setAccountBankName($accountInfo->getBankName())
            ->setAccountNumber($accountInfo->getNumber())
            ->setAccountCountry($accountInfo->getCountry())
            ->setAccountBic($accountInfo->getBic())
            ->setAccountIban($accountInfo->getIban())
            ->setAccountIdentification($accountInfo->getIdentification());

        $directDebitRegistrationTransfer->setAccountInfo($directDebitAccountTransfer);

        return $directDebitRegistrationTransfer;
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    protected function addError(
        Response $apiResponse,
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        if (!$apiResponse->isError()) {
            return $directDebitRegistrationTransfer;
        }

        $errorResponse = $apiResponse->getError();
        $directDebitRegistrationTransfer
            ->setIsError(true)
            ->setError(
                (new HeidelpayResponseErrorTransfer())
                    ->setCode($errorResponse[static::API_RESPONSE_ERROR_CODE_KEY])
                    ->setInternalMessage($errorResponse[static::API_RESPONSE_ERROR_MESSAGE_KEY])
            );

        return $directDebitRegistrationTransfer;
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    protected function addIdentification(
        Response $apiResponse,
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        $directDebitRegistrationTransfer
            ->setRegistrationUniqueId($apiResponse->getIdentification()->getUniqueId())
            ->setTransactionId($apiResponse->getIdentification()->getTransactionId());

        return $directDebitRegistrationTransfer;
    }
}
