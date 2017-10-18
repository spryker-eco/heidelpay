<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface;

interface CreditCardPaymentInterface extends
    PaymentWithAuthorizeInterface,
    PaymentWithExternalResponseInterface,
    PaymentWithCaptureInterface
{

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function register(HeidelpayRequestTransfer $authorizeRequestTransfer);

}
