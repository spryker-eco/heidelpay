<?php

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPayment;
use SprykerEcoTest\Zed\Heidelpay\Business\HeidelpayTestConstants;

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
class SuccessfulSofortPaymentMock extends SofortPayment
{

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorize(HeidelpayRequestTransfer $authorizeRequestTransfer)
    {
        $responseTransfer = new HeidelpayResponseTransfer();
        $responseTransfer->setIsSuccess(true);
        $responseTransfer->setIsError(false);

        $responseTransfer->setIdSalesOrder($authorizeRequestTransfer->getCustomerPurchase()->getIdOrder());
        $responseTransfer->setResultCode(HeidelpayTestConstants::HEIDELPAY_SUCCESS_RESPONSE);
        $responseTransfer->setIdTransactionUnique('some unique transaction');
        $responseTransfer->setProcessingCode('OT.RC.90.00');
        $responseTransfer->setCustomerRedirectUrl(HeidelpayTestConstants::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL);
        $responseTransfer->setPayload('{
                        "processing": {"result": "ACK"}, 
                        "payment": {"code": "OT.RC"}                         
                    }');

        return $responseTransfer;
    }
}