<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Form;

use Spryker\Shared\Heidelpay\HeidelpayConstants;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;

class PaypalAuthorizeSubForm extends AbstractHeidelpaySubForm implements SubFormInterface
{

    const PAYMENT_METHOD = HeidelpayConstants::PAYMENT_METHOD_PAYPAL_AUTHORIZE;

}
