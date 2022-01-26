<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPayment;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\PaymentResponseTransferBuilderTrait;

class UnsuccessfulCreditCardCapturePaymentMock extends CreditCardPayment
{
    use PaymentResponseTransferBuilderTrait;

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $captureRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function register(HeidelpayRequestTransfer $captureRequestTransfer): HeidelpayResponseTransfer
    {
        $response = [];
        $response['payload'] = '{
                        "processing": {"result": "NOK"}, 
                        "payment": {"code": null}                         
                    }';

        $response['processingCode'] = null;

        $response['idTransactionUnique'] = null;
        $response['idSalesOrder'] = $captureRequestTransfer->getCustomerPurchase()->getIdOrder();
        $responseTransfer = $this->getUnsuccessfulHeidelpayTransfer($response);
        $responseTransfer->setResultCode('NOK');
        $responseTransfer->setPaymentFormUrl(null);

        return $responseTransfer;
    }

        /**
         * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $debitRequestTransfer
         *
         * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
         */
    public function capture(HeidelpayRequestTransfer $debitRequestTransfer): HeidelpayResponseTransfer
    {
        $response = [];
        $response['payload'] = '{
                        "processing": {"result": "NOK"}, 
                        "payment": {"code": "CC.RC"}                         
                    }';

        $response['processingCode'] = 'CC.CP.70.30';

        $response['idTransactionUnique'] = 'some unique transaction';
        $response['idSalesOrder'] = $debitRequestTransfer->getCustomerPurchase()->getIdOrder();

        $responseTransfer = $this->getUnsuccessfulHeidelpayTransfer($response);

        return $responseTransfer;
    }
}
