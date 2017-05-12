<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Heidelpay\PhpApi\PaymentMethods\PayPalPaymentMethod;
use Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface;

class PaypalPayment extends BasePayment implements
    PaypalPaymentInterface,
    PaymentWithAuthorizeInterface,
    PaymentWithDebitInterface,
    PaymentWithExternalResponseInterface,
    PaymentWithCaptureInterface
{

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorize(HeidelpayRequestTransfer $authorizeRequestTransfer)
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
    public function capture(HeidelpayRequestTransfer $captureRequestTransfer)
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
    public function debit(HeidelpayRequestTransfer $debitRequestTransfer)
    {
        $paypalMethod = new PayPalPaymentMethod();
        $this->prepareRequest($debitRequestTransfer, $paypalMethod->getRequest());
        $paypalMethod->debit();
        return $this->verifyAndParseResponse($paypalMethod->getResponse());
    }

}
