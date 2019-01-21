<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Heidelpay\PhpPaymentApi\PaymentMethods\PayPalPaymentMethod;

class PaypalPayment extends BasePayment implements PaypalPaymentInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorize(HeidelpayRequestTransfer $authorizeRequestTransfer): HeidelpayResponseTransfer
    {
        $paypalMethod = new PayPalPaymentMethod();
        $this->prepareRequest($authorizeRequestTransfer, $paypalMethod->getRequest());
        $paypalMethod->authorize();
        return $this->verifyAndParseResponse($paypalMethod->getResponse());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $captureRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function capture(HeidelpayRequestTransfer $captureRequestTransfer): HeidelpayResponseTransfer
    {
        $paypalMethod = new PayPalPaymentMethod();
        $this->prepareRequest($captureRequestTransfer, $paypalMethod->getRequest());
        $paypalMethod->capture($captureRequestTransfer->getIdPaymentReference());

        return $this->verifyAndParseResponse($paypalMethod->getResponse());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $debitRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function debit(HeidelpayRequestTransfer $debitRequestTransfer): HeidelpayResponseTransfer
    {
        $paypalMethod = new PayPalPaymentMethod();
        $this->prepareRequest($debitRequestTransfer, $paypalMethod->getRequest());
        $paypalMethod->debit();
        return $this->verifyAndParseResponse($paypalMethod->getResponse());
    }
}
