<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer;
use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseErrorTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Heidelpay\PhpPaymentApi\Exceptions\HashVerificationException;
use Heidelpay\PhpPaymentApi\Request;
use Heidelpay\PhpPaymentApi\Response;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\RequestToHeidelpayInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\ResponseFromHeidelpayInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayConfigInterface;

class BasePayment implements PaymentWithExternalResponseInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\HeidelpayConfigInterface
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\ResponseFromHeidelpayInterface
     */
    protected $responseMapper;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\RequestToHeidelpayInterface
     */
    protected $requestMapper;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\RequestToHeidelpayInterface $requestMapper
     * @param \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\ResponseFromHeidelpayInterface $responseMapper
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfigInterface $config
     */
    public function __construct(
        RequestToHeidelpayInterface $requestMapper,
        ResponseFromHeidelpayInterface $responseMapper,
        HeidelpayConfigInterface $config
    ) {
        $this->requestMapper = $requestMapper;
        $this->responseMapper = $responseMapper;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function processExternalResponse(HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer)
    {
        $apiResponseObject = new Response($externalResponseTransfer->getBody());
        return $this->verifyAndParseResponse($apiResponseObject);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $requestTransfer
     * @param \Heidelpay\PhpPaymentApi\Request $apiRequest
     *
     * @return void
     */
    protected function prepareRequest(HeidelpayRequestTransfer $requestTransfer, Request $apiRequest)
    {
        $this->requestMapper->map($requestTransfer, $apiRequest);
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponseObject
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    protected function verifyAndParseResponse(Response $apiResponseObject)
    {
        $parsedResponseTransfer = $this->convertToHeidelpayResponse($apiResponseObject);

        try {
            $apiResponseObject->verifySecurityHash(
                $this->getApplicationSecret(),
                $apiResponseObject->getIdentification()->getTransactionId()
            );
        } catch (HashVerificationException $exception) {
            $errorTransfer = $this->extractErrorTransferFromException($exception);
            $parsedResponseTransfer->setError($errorTransfer);
            $parsedResponseTransfer->setIsError(true);
        }

        return $parsedResponseTransfer;
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponse
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    protected function convertToHeidelpayResponse(Response $apiResponse)
    {
        $responseTransfer = new HeidelpayResponseTransfer();
        $this->responseMapper->map($apiResponse, $responseTransfer);

        return $responseTransfer;
    }

    /**
     * @return string
     */
    protected function getApplicationSecret()
    {
        return $this->config->getApplicationSecret();
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Exceptions\HashVerificationException $exception
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseErrorTransfer
     */
    protected function extractErrorTransferFromException(HashVerificationException $exception)
    {
        $errorTransfer = new HeidelpayResponseErrorTransfer();
        $errorTransfer->setInternalMessage($exception->getMessage());

        return $errorTransfer;
    }
}
