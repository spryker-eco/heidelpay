<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPayment;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\PaymentResponseTransferBuilderTrait;

class SuccessfulCreditCardCapturePaymentMock extends CreditCardPayment
{

    use PaymentResponseTransferBuilderTrait;

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $captureRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function capture(HeidelpayRequestTransfer $captureRequestTransfer)
    {
        $transfer['payload'] = '{
                        "processing": {"result": "ACK"}, 
                        "payment": {"code": "VA.RC"}                         
                    }';

        $transfer['processingCode'] = "CC.CP.90.00";

        $transfer['idTransactionUnique'] = 'some unique transaction';
        $transfer['idSalesOrder'] = $captureRequestTransfer->getCustomerPurchase()->getIdOrder();
        $responseTransfer = $this->getSuccessfulHeidelpayTransfer($transfer);
        $responseTransfer->setPaymentFormUrl(null);
        return $responseTransfer;
    }

}
