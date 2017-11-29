<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment;

use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Heidelpay\PhpApi\Exceptions\HashVerificationException;
use SprykerEcoTest\Zed\Heidelpay\Business\HeidelpayTestConstants;

trait PaymentResponseTransferBuilderTrait
{

    /**
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    protected function getSuccessfulHeidelpayTransfer(array $response)
    {
        $responseTransfer = new HeidelpayResponseTransfer();
        $responseTransfer->setIsSuccess(true);
        $responseTransfer->setIsError(false);

        $responseTransfer->setIdSalesOrder($response['idSalesOrder']);
        $responseTransfer->setResultCode(HeidelpayTestConstants::HEIDELPAY_SUCCESS_RESPONSE);
        $responseTransfer->setIdTransactionUnique($response['idTransactionUnique']);
        $responseTransfer->setProcessingCode($response['processingCode']);
        $responseTransfer->setCustomerRedirectUrl(HeidelpayTestConstants::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL);
        $responseTransfer->setPayload($response['payload']);
        return $responseTransfer;
    }

    /**
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    protected function getUnsuccessfulHeidelpayTransfer(array $response)
    {
        $responseTransfer = new HeidelpayResponseTransfer();
        $responseTransfer->setIsSuccess(false);
        $responseTransfer->setIsError(true);

        $exception = new HashVerificationException('Custom error');
        $errorTransfer = $this->extractErrorTransferFromException($exception);
        $responseTransfer->setError($errorTransfer);

        $responseTransfer->setIdSalesOrder($response['idSalesOrder']);
        $responseTransfer->setResultCode(HeidelpayTestConstants::HEIDELPAY_UNSUCCESS_RESPONSE);
        $responseTransfer->setIdTransactionUnique($response['idTransactionUnique']);
        $responseTransfer->setProcessingCode($response['processingCode']);
        $responseTransfer->setCustomerRedirectUrl(HeidelpayTestConstants::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL);
        $responseTransfer->setPayload($response['payload']);

        return $responseTransfer;
    }

}
