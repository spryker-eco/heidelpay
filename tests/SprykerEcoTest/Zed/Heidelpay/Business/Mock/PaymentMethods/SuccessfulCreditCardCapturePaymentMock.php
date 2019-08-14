<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPayment;
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\PaymentResponseTransferBuilderTrait;

class SuccessfulCreditCardCapturePaymentMock extends CreditCardPayment
{
    use PaymentResponseTransferBuilderTrait;

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $captureRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function register(HeidelpayRequestTransfer $captureRequestTransfer): HeidelpayResponseTransfer
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
        $responseTransfer->setPaymentFormUrl(HeidelpayTestConfig::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $captureRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function capture(HeidelpayRequestTransfer $captureRequestTransfer): HeidelpayResponseTransfer
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
