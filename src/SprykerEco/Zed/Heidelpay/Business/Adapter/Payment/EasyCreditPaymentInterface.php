<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeOnRegistrationInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithFinalizeInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithInitializeInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithReservationInterface;

interface EasyCreditPaymentInterface extends
    PaymentWithInitializeInterface,
    PaymentWithExternalResponseInterface,
    PaymentWithReservationInterface,
    PaymentWithAuthorizeOnRegistrationInterface,
    PaymentWithFinalizeInterface
{
}
