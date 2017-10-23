<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Form;

use SprykerEco\Shared\Heidelpay\HeidelpayConfig;

class PaypalDebitSubForm extends AbstractHeidelpaySubForm
{
    const PAYMENT_METHOD = HeidelpayConfig::PAYMENT_METHOD_PAYPAL_DEBIT;
}
