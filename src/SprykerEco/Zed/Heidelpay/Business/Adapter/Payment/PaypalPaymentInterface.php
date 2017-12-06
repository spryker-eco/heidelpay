<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface;

interface PaypalPaymentInterface extends
    PaymentWithDebitInterface,
    PaymentWithAuthorizeInterface,
    PaymentWithExternalResponseInterface,
    PaymentWithCaptureInterface
{
}
