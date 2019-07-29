<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithFinalizeInterface;

interface InvoiceSecuredB2cPaymentInterface extends
    PaymentWithAuthorizeInterface,
    PaymentWithExternalResponseInterface,
    PaymentWithFinalizeInterface
{
}
