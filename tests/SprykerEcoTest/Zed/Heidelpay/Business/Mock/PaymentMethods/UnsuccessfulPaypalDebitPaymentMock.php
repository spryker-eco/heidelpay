<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\PaypalPayment;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\PaymentResponseTransferBuilderTrait;

class UnsuccessfulPaypalDebitPaymentMock extends PaypalPayment
{

    use PaymentResponseTransferBuilderTrait;
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $debitRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function debit(HeidelpayRequestTransfer $debitRequestTransfer)
    {
        $transfer['payload'] = '{
                        "processing": {"result": "NOK"}, 
                        "payment": {"code": "VA.RC"}                         
                    }';

        $transfer['processingCode'] = 'VA.RC.90.00';

        $transfer['idTransactionUnique'] = 'some unique transaction';
        $transfer['idSalesOrder'] = $debitRequestTransfer->getCustomerPurchase()->getIdOrder();

        $responseTransfer = $this->getUnsuccessfulHeidelpayTransfer($transfer);

        return $responseTransfer;
    }

}
