<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Heidelpay\PhpPaymentApi\PaymentMethods\CreditCardPaymentMethod;

class CreditCardPayment extends BasePayment implements
    CreditCardPaymentInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $registerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function register(HeidelpayRequestTransfer $registerRequestTransfer): HeidelpayResponseTransfer
    {
        $creditCardMethod = new CreditCardPaymentMethod();
        $this->prepareRequest($registerRequestTransfer, $creditCardMethod->getRequest());

        $creditCardMethod->registration(
            $this->config->getCreditCardPaymentFrameOrigin(),
            $this->config->getCreditCardPaymentFramePreventAsyncRedirect(),
            $this->config->getCreditCardPaymentFrameCustomCssUrl()
        );

        return $this->verifyAndParseResponse($creditCardMethod->getResponse());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorize(HeidelpayRequestTransfer $authorizeRequestTransfer): HeidelpayResponseTransfer
    {
        $creditCardMethod = new CreditCardPaymentMethod();
        $this->prepareRequest($authorizeRequestTransfer, $creditCardMethod->getRequest());

        $creditCardMethod->authorizeOnRegistration($authorizeRequestTransfer->getIdPaymentRegistration());

        return $this->verifyAndParseResponse($creditCardMethod->getResponse());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $captureRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function capture(HeidelpayRequestTransfer $captureRequestTransfer): HeidelpayResponseTransfer
    {
        $creditCardMethod = new CreditCardPaymentMethod();
        $this->prepareRequest($captureRequestTransfer, $creditCardMethod->getRequest());
        $creditCardMethod->capture($captureRequestTransfer->getIdPaymentReference());

        return $this->verifyAndParseResponse($creditCardMethod->getResponse());
    }
}
