<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\DirectDebitPayment;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\PaymentResponseTransferBuilderTrait;

class UnsuccessfulDirectDebitPaymentMock extends DirectDebitPayment
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
    public function debitOnRegistration(HeidelpayRequestTransfer $debitRequestTransfer): HeidelpayResponseTransfer
    {
        $response['payload'] = '{
                        "processing": {"result": "NOK"}, 
                        "payment": {"code": "DD.DE"}                         
                    }';

        $response['processingCode'] = 'HP.DE.70.30';

        $response['idTransactionUnique'] = 'some unique transaction';
        $response['idSalesOrder'] = $debitRequestTransfer->getCustomerPurchase()->getIdOrder();

        $responseTransfer = $this->getUnsuccessfulHeidelpayTransfer($response);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function refund(HeidelpayRequestTransfer $reservationRequestTransfer): HeidelpayResponseTransfer
    {
        $response['payload'] = '{
                        "processing": {"result": "ACK"}, 
                        "payment": {"code": "DD.RF"}                         
                    }';

        $response['processingCode'] = 'HP.RF.70.15';

        $response['idTransactionUnique'] = 'some unique transaction';
        $response['idSalesOrder'] = $reservationRequestTransfer->getCustomerPurchase()->getIdOrder();

        $responseTransfer = $this->getUnsuccessfulHeidelpayTransfer($response);

        return $responseTransfer;
    }
}
