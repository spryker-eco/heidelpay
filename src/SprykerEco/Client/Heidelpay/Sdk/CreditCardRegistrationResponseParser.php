<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Heidelpay\Sdk;

use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseErrorTransfer;
use Heidelpay\PhpPaymentApi\Exceptions\HashVerificationException;
use Heidelpay\PhpPaymentApi\Response;
use SprykerEco\Client\Heidelpay\HeidelpayConfig;
use SprykerEco\Client\Heidelpay\Mapper\ApiResponseToRegistrationResponseTransferInterface;

class CreditCardRegistrationResponseParser implements CreditCardRegistrationResponseParserInterface
{
    const ERROR_CODE_INVALID_RESPONSE = 'invalid-response';

    /**
     * @var \SprykerEco\Client\Heidelpay\HeidelpayConfig
     */
    protected $config;

    /**
     * @var \SprykerEco\Client\Heidelpay\Mapper\ApiResponseToRegistrationResponseTransferInterface
     */
    protected $apiResponseToRegistrationResponseMapper;

    /**
     * @param \SprykerEco\Client\Heidelpay\Mapper\ApiResponseToRegistrationResponseTransferInterface $apiResponseToRegistrationResponseMapper
     * @param \SprykerEco\Client\Heidelpay\HeidelpayConfig $config
     */
    public function __construct(
        ApiResponseToRegistrationResponseTransferInterface $apiResponseToRegistrationResponseMapper,
        HeidelpayConfig $config
    ) {
        $this->config = $config;
        $this->apiResponseToRegistrationResponseMapper = $apiResponseToRegistrationResponseMapper;
    }

    /**
     * @param array $responseArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer
     */
    public function parseExternalResponse(array $responseArray): HeidelpayRegistrationRequestTransfer
    {
        $registrationRequestTransfer = new HeidelpayRegistrationRequestTransfer();

        try {
            $apiResponseObject = $this->getValidatedApiResponseObject($responseArray);
            $this->hydrateResponseToTransfer($apiResponseObject, $registrationRequestTransfer);
        } catch (HashVerificationException $exception) {
            $this->hydrateValidationErrorToRequest($registrationRequestTransfer);
        }

        return $registrationRequestTransfer;
    }

    /**
     * @param array $apiResponseArray
     *
     * @return \Heidelpay\PhpPaymentApi\Response
     */
    protected function getValidatedApiResponseObject(array $apiResponseArray): Response
    {
        $apiResponse = new Response($apiResponseArray);

        $apiResponse->verifySecurityHash(
            $this->getApplicationSecret(),
            $apiResponse->getIdentification()->getTransactionId()
        );

        return $apiResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return void
     */
    protected function hydrateValidationErrorToRequest(HeidelpayRegistrationRequestTransfer $registrationRequestTransfer): void
    {
        $errorTransfer = new HeidelpayResponseErrorTransfer();
        $errorTransfer->setCode(static::ERROR_CODE_INVALID_RESPONSE);

        $this->apiResponseToRegistrationResponseMapper
            ->hydrateErrorToRegistrationRequest($registrationRequestTransfer, $errorTransfer);
    }

    /**
     * @return string
     */
    protected function getApplicationSecret(): string
    {
        return $this->config->getApplicationSecret();
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponseObject
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return void
     */
    protected function hydrateResponseToTransfer(
        Response $apiResponseObject,
        HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
    ): void {
        $this->apiResponseToRegistrationResponseMapper
            ->map(
                $apiResponseObject,
                $registrationRequestTransfer
            );
    }
}
