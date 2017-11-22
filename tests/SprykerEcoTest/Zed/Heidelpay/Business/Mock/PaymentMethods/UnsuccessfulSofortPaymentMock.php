<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods;


use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Heidelpay\PhpApi\Exceptions\HashVerificationException;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPayment;
use SprykerEcoTest\Zed\Heidelpay\Business\HeidelpayTestConstants;

class UnsuccessfulSofortPaymentMock extends SofortPayment
{

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorize(HeidelpayRequestTransfer $authorizeRequestTransfer)
    {
        $responseTransfer = new HeidelpayResponseTransfer();
        $responseTransfer->setIsSuccess(false);
        $responseTransfer->setIsError(true);

        $exception = new HashVerificationException('Custom error');
        $errorTransfer = $this->extractErrorTransferFromException($exception);
        $responseTransfer->setError($errorTransfer);

        $responseTransfer->setIdSalesOrder($authorizeRequestTransfer->getCustomerPurchase()->getIdOrder());
        $responseTransfer->setResultCode(HeidelpayTestConstants::HEIDELPAY_UNSUCCESS_RESPONSE);
        $responseTransfer->setIdTransactionUnique('some unique transaction');
        $responseTransfer->setProcessingCode('OT.RC.90.00');
        $responseTransfer->setCustomerRedirectUrl(HeidelpayTestConstants::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL);
        $responseTransfer->setPayload('{
                        "processing": {"result": "null"}, 
                        "payment": {"code": "OT.RC"}                         
                    }');

        return $responseTransfer;
    }
}