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
     * @param array $transfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    protected function getSuccessfulHeidelpayTransfer(array $transfer)
    {
        $responseTransfer = new HeidelpayResponseTransfer();
        $responseTransfer->setIsSuccess(true);
        $responseTransfer->setIsError(false);

        $responseTransfer->setIdSalesOrder($transfer['idSalesOrder']);
        $responseTransfer->setResultCode(HeidelpayTestConstants::HEIDELPAY_SUCCESS_RESPONSE);
        $responseTransfer->setIdTransactionUnique($transfer['idTransactionUnique']);
        $responseTransfer->setProcessingCode($transfer['processingCode']);
        $responseTransfer->setCustomerRedirectUrl(HeidelpayTestConstants::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL);
        $responseTransfer->setPayload($transfer['payload']);
        return $responseTransfer;
    }

    /**
     * @param array $transfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    protected function getUnsuccessfulHeidelpayTransfer(array $transfer)
    {
        $responseTransfer = new HeidelpayResponseTransfer();
        $responseTransfer->setIsSuccess(false);
        $responseTransfer->setIsError(true);

        $exception = new HashVerificationException('Custom error');
        $errorTransfer = $this->extractErrorTransferFromException($exception);
        $responseTransfer->setError($errorTransfer);

        $responseTransfer->setIdSalesOrder($transfer['idSalesOrder']);
        $responseTransfer->setResultCode(HeidelpayTestConstants::HEIDELPAY_UNSUCCESS_RESPONSE);
        $responseTransfer->setIdTransactionUnique($transfer['idTransactionUnique']);
        $responseTransfer->setProcessingCode($transfer['processingCode']);
        $responseTransfer->setCustomerRedirectUrl(HeidelpayTestConstants::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL);
        $responseTransfer->setPayload($transfer['payload']);

        return $responseTransfer;
    }

}
