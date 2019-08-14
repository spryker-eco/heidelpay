<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Heidelpay\PhpPaymentApi\PaymentMethods\DirectDebitPaymentMethod;

class DirectDebitPayment extends BasePayment implements DirectDebitPaymentInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $registerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function register(HeidelpayRequestTransfer $registerRequestTransfer): HeidelpayResponseTransfer
    {
        $directDebitPaymentMethod = new DirectDebitPaymentMethod();
        $this->prepareRequest($registerRequestTransfer, $directDebitPaymentMethod->getRequest());

        $directDebitPaymentMethod->registration();

        return $this->verifyAndParseResponse($directDebitPaymentMethod->getResponse());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $debitOnRegistrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function debitOnRegistration(HeidelpayRequestTransfer $debitOnRegistrationRequestTransfer): HeidelpayResponseTransfer
    {
        $directDebitPaymentMethod = new DirectDebitPaymentMethod();
        $this->prepareRequest($debitOnRegistrationRequestTransfer, $directDebitPaymentMethod->getRequest());

        $directDebitPaymentMethod->debitOnRegistration($debitOnRegistrationRequestTransfer->getIdPaymentRegistration());

        return $this->verifyAndParseResponse($directDebitPaymentMethod->getResponse());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $refundRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function refund(HeidelpayRequestTransfer $refundRequestTransfer)
    {
        $directDebitPaymentMethod = new DirectDebitPaymentMethod();
        $this->prepareRequest($refundRequestTransfer, $directDebitPaymentMethod->getRequest());

        $directDebitPaymentMethod->refund($refundRequestTransfer->getIdPaymentReference());

        return $this->verifyAndParseResponse($directDebitPaymentMethod->getResponse());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorize(HeidelpayRequestTransfer $authorizeRequestTransfer): HeidelpayResponseTransfer
    {
        $directDebitPaymentMethod = new DirectDebitPaymentMethod();
        $this->prepareRequest($authorizeRequestTransfer, $directDebitPaymentMethod->getRequest());

        $directDebitPaymentMethod->authorizeOnRegistration($authorizeRequestTransfer->getIdPaymentRegistration());

        return $this->verifyAndParseResponse($directDebitPaymentMethod->getResponse());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $captureRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function capture(HeidelpayRequestTransfer $captureRequestTransfer): HeidelpayResponseTransfer
    {
        $directDebitPaymentMethod = new DirectDebitPaymentMethod();
        $this->prepareRequest($captureRequestTransfer, $directDebitPaymentMethod->getRequest());

        $directDebitPaymentMethod->capture($captureRequestTransfer->getIdPaymentReference());

        return $this->verifyAndParseResponse($directDebitPaymentMethod->getResponse());
    }
}
