<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface;

interface DirectDebitPaymentInterface extends
    PaymentWithAuthorizeInterface,
    PaymentWithExternalResponseInterface,
    PaymentWithCaptureInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function register(HeidelpayRequestTransfer $authorizeRequestTransfer): HeidelpayResponseTransfer;
}
