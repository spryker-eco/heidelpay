<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPayment;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\PaymentResponseTransferBuilderTrait;

class UnsuccessfulSofortPaymentMock extends SofortPayment
{
    use PaymentResponseTransferBuilderTrait;

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorize(HeidelpayRequestTransfer $authorizeRequestTransfer): HeidelpayResponseTransfer
    {
        $response = [];
        $response['payload'] = '{
                        "processing": {"result": "null"}, 
                        "payment": {"code": "OT.RC"}                         
                    }';

        $response['processingCode'] = 'OT.RC.90.00';

        $response['idTransactionUnique'] = 'some unique transaction';
        $response['idSalesOrder'] = $authorizeRequestTransfer->getCustomerPurchase()->getIdOrder();

        $responseTransfer = $this->getUnsuccessfulHeidelpayTransfer($response);

        return $responseTransfer;
    }
}
