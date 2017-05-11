<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Heidelpay\Sdk;

use Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer;
use Generated\Shared\Transfer\HeidelpayResponseErrorTransfer;
use Heidelpay\PhpApi\Exceptions\HashVerificationException;
use Heidelpay\PhpApi\Response;
use Spryker\Client\Heidelpay\HeidelpayConfig;
use Spryker\Client\Heidelpay\Mapper\ApiResponseToRegistrationResponseTransferInterface;

class CreditCardRegistrationResponseParser implements CreditCardRegistrationResponseParserInterface
{

    const ERROR_CODE_INVALID_RESPONSE = 'invalid-response';

    /**
     * @var \Spryker\Zed\Heidelpay\HeidelpayConfig
     */
    protected $config;

    /**
     * @var \Spryker\Client\Heidelpay\Mapper\ApiResponseToRegistrationResponseTransferInterface
     */
    protected $apiResponseToRegistrationResponseMapper;

    /**
     * @param \Spryker\Client\Heidelpay\Mapper\ApiResponseToRegistrationResponseTransferInterface $apiResponseToRegistrationResponseMapper
     * @param \Spryker\Client\Heidelpay\HeidelpayConfig $config
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
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer
     */
    public function parseExternalResponse(array $responseArray)
    {
        $registrationResponseTransfer = new HeidelpayRegistrationResponseTransfer();

        try {
            $apiResponseObject = $this->getValidatedApiResponseObject($responseArray);
            $this->hydrateResponseToTransfer($apiResponseObject, $registrationResponseTransfer);
        } catch (HashVerificationException $exception) {
            $this->hydrateValidationErrorToResponse($registrationResponseTransfer);
        }

        return $registrationResponseTransfer;
    }

    /**
     * @param array $apiResponseArray
     *
     * @return \Heidelpay\PhpApi\Response
     */
    protected function getValidatedApiResponseObject(array $apiResponseArray)
    {
        $apiResponse = new Response($apiResponseArray);

        $apiResponse->verifySecurityHash(
            $this->getApplicationSecret(),
            $apiResponse->getIdentification()->getTransactionId()
        );

        return $apiResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer $registrationResponseTransfer
     *
     * @return void
     */
    protected function hydrateValidationErrorToResponse(HeidelpayRegistrationResponseTransfer $registrationResponseTransfer)
    {
        $errorTransfer = new HeidelpayResponseErrorTransfer();
        $errorTransfer->setCode(static::ERROR_CODE_INVALID_RESPONSE);

        $this->apiResponseToRegistrationResponseMapper
            ->hydrateErrorToRegistrationResponse($registrationResponseTransfer, $errorTransfer);
    }

    /**
     * @return string
     */
    protected function getApplicationSecret()
    {
        return $this->config->getApplicationSecret();
    }

    /**
     * @param \Heidelpay\PhpApi\Response $apiResponseObject
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer $registrationResponseTransfer
     *
     * @return void
     */
    protected function hydrateResponseToTransfer(
        Response $apiResponseObject,
        HeidelpayRegistrationResponseTransfer $registrationResponseTransfer
    ) {
        $this->apiResponseToRegistrationResponseMapper
            ->map(
                $apiResponseObject,
                $registrationResponseTransfer
            );
    }

}
