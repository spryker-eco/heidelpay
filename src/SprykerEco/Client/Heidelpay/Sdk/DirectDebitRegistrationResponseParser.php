<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay\Sdk;

use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayResponseErrorTransfer;
use Heidelpay\PhpPaymentApi\Exceptions\HashVerificationException;
use Heidelpay\PhpPaymentApi\Response;
use SprykerEco\Client\Heidelpay\HeidelpayConfig;
use SprykerEco\Client\Heidelpay\Mapper\DirectDebitRegistrationResponseMapperInterface;

class DirectDebitRegistrationResponseParser implements DirectDebitRegistrationResponseParserInterface
{
    /**
     * @var string
     */
    public const ERROR_CODE_INVALID_RESPONSE = 'invalid-response';

    /**
     * @var \SprykerEco\Client\Heidelpay\Mapper\DirectDebitRegistrationResponseMapperInterface
     */
    protected $registrationResponseMapper;

    /**
     * @var \SprykerEco\Client\Heidelpay\HeidelpayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Client\Heidelpay\Mapper\DirectDebitRegistrationResponseMapperInterface $registrationResponseMapper
     * @param \SprykerEco\Client\Heidelpay\HeidelpayConfig $config
     */
    public function __construct(
        DirectDebitRegistrationResponseMapperInterface $registrationResponseMapper,
        HeidelpayConfig $config
    ) {
        $this->registrationResponseMapper = $registrationResponseMapper;
        $this->config = $config;
    }

    /**
     * @param array<string> $responseArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function parseResponse(array $responseArray): HeidelpayDirectDebitRegistrationTransfer
    {
        try {
            $apiResponseObject = $this->getValidatedApiResponseObject($responseArray);

            return $this->createDirectDebitRegistrationResponseTransfer($apiResponseObject);
        } catch (HashVerificationException $exception) {
            return $this->createDirectDebitRegistrationResponseTransferWithError();
        }
    }

    /**
     * @param array<string> $apiResponseArray
     *
     * @return \Heidelpay\PhpPaymentApi\Response
     */
    protected function getValidatedApiResponseObject(array $apiResponseArray): Response
    {
        $apiResponse = new Response($apiResponseArray);

        $apiResponse->verifySecurityHash(
            $this->config->getApplicationSecret(),
            $apiResponse->getIdentification()->getTransactionId()
        );

        return $apiResponse;
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponseObject
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    protected function createDirectDebitRegistrationResponseTransfer(
        Response $apiResponseObject
    ): HeidelpayDirectDebitRegistrationTransfer {
        return $this->registrationResponseMapper
            ->mapApiResponseToDirectDebitRegistrationResponseTransfer(
                $apiResponseObject,
                new HeidelpayDirectDebitRegistrationTransfer()
            );
    }

    /**
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    protected function createDirectDebitRegistrationResponseTransferWithError(): HeidelpayDirectDebitRegistrationTransfer
    {
        return (new HeidelpayDirectDebitRegistrationTransfer())
            ->setIsError(true)
            ->setError(
                (new HeidelpayResponseErrorTransfer())
                    ->setCode(static::ERROR_CODE_INVALID_RESPONSE)
            );
    }
}
