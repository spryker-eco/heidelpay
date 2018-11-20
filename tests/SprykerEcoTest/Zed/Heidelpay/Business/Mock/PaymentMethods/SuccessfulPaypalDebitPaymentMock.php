<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\PaypalPayment;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\PaymentResponseTransferBuilderTrait;

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
class SuccessfulPaypalDebitPaymentMock extends PaypalPayment
{
    use PaymentResponseTransferBuilderTrait;
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $debitRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function debit(HeidelpayRequestTransfer $debitRequestTransfer): HeidelpayResponseTransfer
    {
        $response['payload'] = '{
                        "processing": {"result": "ACK"}, 
                        "payment": {"code": "VA.RC"}                         
                    }';

        $response['processingCode'] = 'VA.RC.90.00';

        $response['idTransactionUnique'] = 'some unique transaction';
        $response['idSalesOrder'] = $debitRequestTransfer->getCustomerPurchase()->getIdOrder();

        $responseTransfer = $this->getSuccessfulHeidelpayTransfer($response);

        return $responseTransfer;
    }
}
