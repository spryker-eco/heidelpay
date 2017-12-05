<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPayment;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\PaymentResponseTransferBuilderTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\HeidelpayTestConstants;

class SuccessfulCreditCardCapturePaymentMock extends CreditCardPayment
{
    use PaymentResponseTransferBuilderTrait;

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $captureRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function register(HeidelpayRequestTransfer $captureRequestTransfer)
    {
        $response['payload'] = '{
                        "processing": {"result": "ACK"}, 
                        "payment": {"code": null}                         
                    }';

        $response['processingCode'] = null;

        $response['idTransactionUnique'] = null;
        $response['idSalesOrder'] = $captureRequestTransfer->getCustomerPurchase()->getIdOrder();
        $responseTransfer = $this->getSuccessfulHeidelpayTransfer($response);
        $responseTransfer->setResultCode('ACK');
        $responseTransfer->setPaymentFormUrl(HeidelpayTestConstants::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL);
        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $captureRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function capture(HeidelpayRequestTransfer $captureRequestTransfer)
    {
        $response['payload'] = '{
                        "processing": {"result": "ACK"}, 
                        "payment": {"code": "VA.RC"}                         
                    }';

        $response['processingCode'] = "CC.CP.90.00";

        $response['idTransactionUnique'] = 'some unique transaction';
        $response['idSalesOrder'] = $captureRequestTransfer->getCustomerPurchase()->getIdOrder();
        $responseTransfer = $this->getSuccessfulHeidelpayTransfer($response);
        $responseTransfer->setPaymentFormUrl(null);
        return $responseTransfer;
    }
}
