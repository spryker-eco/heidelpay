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
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $initializeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function initialize(HeidelpayRequestTransfer $initializeRequestTransfer)
    {
        $easyCreditMethod = new EasyCreditPaymentMethod();
        $this->prepareRequest($initializeRequestTransfer, $easyCreditMethod->getRequest());
        $easyCreditMethod->initialize();
        return $this->verifyAndParseResponse($easyCreditMethod->getResponse());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function reservation(HeidelpayRequestTransfer $reservationRequestTransfer)
    {
        $easyCreditMethod = new EasyCreditPaymentMethod();
        $this->prepareRequest($reservationRequestTransfer, $easyCreditMethod->getRequest());
        return $this->verifyAndParseResponse($easyCreditMethod->getResponse());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $finalizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function finalize(HeidelpayRequestTransfer $finalizeRequestTransfer)
    {
        $easyCreditMethod = new EasyCreditPaymentMethod();
        $this->prepareRequest($finalizeRequestTransfer, $easyCreditMethod->getRequest());
        $easyCreditMethod->finalize($finalizeRequestTransfer->getIdPaymentRegistration());
        return $this->verifyAndParseResponse($easyCreditMethod->getResponse());
    }
}
