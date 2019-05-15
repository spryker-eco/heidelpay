<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock\PaymentMethods;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
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
                        "payment": {"code": "HP.PI"} 
                    }';

        $response['processingCode'] = 'HP.PI.90.00';
        $response['idTransactionUnique'] = 'some unique transaction';
        $response['idSalesOrder'] = $authorizeRequestTransfer->getCustomerPurchase()->getIdOrder();

        $responseTransfer = $this->getUnsuccessfulHeidelpayTransfer($response);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $initializeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function initialize(HeidelpayRequestTransfer $initializeRequestTransfer): HeidelpayResponseTransfer
    {
        $response['payload'] = '{
                        "processing": {"result": "NOK"},
                        "payment": {"code": "HP.INI"}
                    }';

        $response['processingCode'] = 'HP.INI.90.44';
        $response['idTransactionUnique'] = 'some unique transaction';
        $response['idSalesOrder'] = $initializeRequestTransfer->getCustomerPurchase()->getIdOrder();

        $responseTransfer = $this->getUnsuccessfulHeidelpayTransfer($response);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $finalizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function finalize(HeidelpayRequestTransfer $finalizeRequestTransfer): HeidelpayResponseTransfer
    {
        $response['payload'] = '{
                        "processing": {"result": "NOK"},
                        "payment": {"code": "HP.FI"}
                    }';

        $response['processingCode'] = 'HP.FI.60.95';
        $response['idTransactionUnique'] = 'some unique transaction';
        $response['idSalesOrder'] = $finalizeRequestTransfer->getCustomerPurchase()->getIdOrder();

        $responseTransfer = $this->getSuccessfulHeidelpayTransfer($response);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function reserve(HeidelpayRequestTransfer $reservationRequestTransfer): HeidelpayResponseTransfer
    {
        $response['payload'] = '{
                        "processing": {"result": "NOK"}, 
                        "payment": {"code": "HP.PI"}
                    }';

        $response['processingCode'] = 'HP.PI.90.65';
        $response['idTransactionUnique'] = 'some unique transaction';
        $response['idSalesOrder'] = $reservationRequestTransfer->getCustomerPurchase()->getIdOrder();

        $responseTransfer = $this->getSuccessfulHeidelpayTransfer($response);

        return $responseTransfer;
    }
}
