<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment;

use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Heidelpay\PhpPaymentApi\Exceptions\HashVerificationException;
use SprykerEcoTest\Zed\Heidelpay\Business\HeidelpayTestConfig;

trait PaymentResponseTransferBuilderTrait
{
    /**
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    protected function getSuccessfulHeidelpayTransfer(array $response): HeidelpayResponseTransfer
    {
        $responseTransfer = new HeidelpayResponseTransfer();
        $responseTransfer->setIsSuccess(true);
        $responseTransfer->setIsError(false);

        $responseTransfer->setIdSalesOrder($response['idSalesOrder']);
        $responseTransfer->setResultCode(HeidelpayTestConfig::HEIDELPAY_SUCCESS_RESPONSE);
        $responseTransfer->setIdTransactionUnique($response['idTransactionUnique']);
        $responseTransfer->setProcessingCode($response['processingCode']);
        $responseTransfer->setCustomerRedirectUrl(HeidelpayTestConfig::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL);
        $responseTransfer->setPayload($response['payload']);

        return $responseTransfer;
    }

    /**
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    protected function getUnsuccessfulHeidelpayTransfer(array $response): HeidelpayResponseTransfer
    {
        $responseTransfer = new HeidelpayResponseTransfer();
        $responseTransfer->setIsSuccess(false);
        $responseTransfer->setIsError(true);

        $exception = new HashVerificationException('Custom error');
        $errorTransfer = $this->extractErrorTransferFromException($exception);
        $responseTransfer->setError($errorTransfer);

        $responseTransfer->setIdSalesOrder($response['idSalesOrder']);
        $responseTransfer->setResultCode(HeidelpayTestConfig::HEIDELPAY_UNSUCCESS_RESPONSE);
        $responseTransfer->setIdTransactionUnique($response['idTransactionUnique']);
        $responseTransfer->setProcessingCode($response['processingCode']);
        $responseTransfer->setCustomerRedirectUrl(HeidelpayTestConfig::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL);
        $responseTransfer->setPayload($response['payload']);

        return $responseTransfer;
    }
}
