<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Heidelpay\PhpPaymentApi\PaymentMethods\EasyCreditPaymentMethod;

class EasyCreditPayment extends BasePayment implements EasyCreditPaymentInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorizeOnRegistration(HeidelpayRequestTransfer $authorizeRequestTransfer)
    {
        $easyCreditMethod = new EasyCreditPaymentMethod();
        $this->prepareRequest($authorizeRequestTransfer, $easyCreditMethod->getRequest());
        $easyCreditMethod->authorizeOnRegistration($authorizeRequestTransfer->getIdPaymentRegistration());
        return $this->verifyAndParseResponse($easyCreditMethod->getResponse());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function initialize(HeidelpayRequestTransfer $authorizeRequestTransfer)
    {
        $easyCreditMethod = new EasyCreditPaymentMethod();
        $this->prepareRequest($authorizeRequestTransfer, $easyCreditMethod->getRequest());
        $easyCreditMethod->initialize();
        return $this->verifyAndParseResponse($easyCreditMethod->getResponse());
    }
}
