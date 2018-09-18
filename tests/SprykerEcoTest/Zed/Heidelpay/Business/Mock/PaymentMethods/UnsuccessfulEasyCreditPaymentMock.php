<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\EasyCreditPayment;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\PaymentResponseTransferBuilderTrait;

class UnsuccessfulEasyCreditPaymentMock extends EasyCreditPayment
{
    use PaymentResponseTransferBuilderTrait;

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorizeOnRegistration(HeidelpayRequestTransfer $authorizeRequestTransfer)
    {
        $response['payload'] = '{
                        "processing": {"result": "NOK"}, 
                        "payment": {"code": "OT.RC"}                         
                    }';

        $response['processingCode'] = 'OT.RC.90.00';

        $response['idTransactionUnique'] = 'some unique transaction';
        $response['idSalesOrder'] = $authorizeRequestTransfer->getCustomerPurchase()->getIdOrder();

        $responseTransfer = $this->getUnsuccessfulHeidelpayTransfer($response);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function initialize(HeidelpayRequestTransfer $authorizeRequestTransfer)
    {
        $response['payload'] = '{
                        "processing": {"result": "NOK"}, 
                        "payment": {"code": "OT.RC"}                         
                    }';

        $response['processingCode'] = 'OT.RC.90.00';

        $response['idTransactionUnique'] = 'some unique transaction';
        $response['idSalesOrder'] = 1000;

        $responseTransfer = $this->getUnsuccessfulHeidelpayTransfer($response);

        return $responseTransfer;
    }
}
