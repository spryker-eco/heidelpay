<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
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
    public function initialize(HeidelpayRequestTransfer $initializeRequestTransfer): HeidelpayResponseTransfer
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
/////////////
        $request = $easyCreditMethod->getRequest();
        $request->getFrontend()->setEnabled('FALSE');
        $async = $reservationRequestTransfer->getAsync();
        $async->setResponseUrl(null);
        $reservationRequestTransfer->setAsync($async);
/////////////
        $this->prepareRequest($reservationRequestTransfer, $request);
        $easyCreditMethod->authorizeOnRegistration($reservationRequestTransfer->getIdPaymentReference());

        return $this->verifyAndParseResponse($easyCreditMethod->getResponse());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $finalizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function finalize(HeidelpayRequestTransfer $finalizeRequestTransfer): HeidelpayResponseTransfer
    {
        $easyCreditMethod = new EasyCreditPaymentMethod();
        $this->prepareRequest($finalizeRequestTransfer, $easyCreditMethod->getRequest());
        $easyCreditMethod->finalize('31HA07BC814A66978BC90CB3EF663058');

        return $this->verifyAndParseResponse($easyCreditMethod->getResponse());
    }
}
