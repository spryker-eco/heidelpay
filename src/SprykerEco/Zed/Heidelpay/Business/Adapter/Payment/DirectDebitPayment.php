<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Heidelpay\PhpPaymentApi\PaymentMethods\DirectDebitPaymentMethod;

class DirectDebitPayment extends BasePayment implements
    DirectDebitPaymentInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $registerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function register(HeidelpayRequestTransfer $registerRequestTransfer): HeidelpayResponseTransfer
    {
        $directDebit = new DirectDebitPaymentMethod();
        $this->prepareRequest($registerRequestTransfer, $directDebit->getRequest());

        $directDebit->registration();

        return $this->verifyAndParseResponse($directDebit->getResponse());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorize(HeidelpayRequestTransfer $authorizeRequestTransfer): HeidelpayResponseTransfer
    {
        $creditCardMethod = new DirectDebitPaymentMethod();
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
        $creditCardMethod = new DirectDebitPaymentMethod();
        $this->prepareRequest($captureRequestTransfer, $creditCardMethod->getRequest());
        $creditCardMethod->capture($captureRequestTransfer->getIdPaymentReference());

        return $this->verifyAndParseResponse($creditCardMethod->getResponse());
    }
}
