<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Heidelpay\PhpApi\PaymentMethods\CreditCardPaymentMethod;
use Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface;

class CreditCardPayment extends BasePayment implements
    CreditCardPaymentInterface,
    PaymentWithAuthorizeInterface,
    PaymentWithExternalResponseInterface,
    PaymentWithCaptureInterface
{

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $registerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function register(HeidelpayRequestTransfer $registerRequestTransfer)
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
    public function authorize(HeidelpayRequestTransfer $authorizeRequestTransfer)
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
    public function capture(HeidelpayRequestTransfer $captureRequestTransfer)
    {
        $creditCardMethod = new CreditCardPaymentMethod();
        $this->prepareRequest($captureRequestTransfer, $creditCardMethod->getRequest());
        $creditCardMethod->capture($captureRequestTransfer->getIdPaymentReference());

        return $this->verifyAndParseResponse($creditCardMethod->getResponse());
    }

}
