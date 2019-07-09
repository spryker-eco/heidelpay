<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Form;

use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;

class InvoiceSecuredB2CSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    protected const PAYMENT_METHOD_TEMPLATE_PATH = 'invoice-secured-b2c';

    /**
     * @return string
     */
    public function getProviderName()
    {
        return HeidelpayConfig::PROVIDER_NAME;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return HeidelpayConfig::PAYMENT_METHOD_INVOICE_SECURED_B2C;
    }

    /**
     * Specifies the property name of the payment transfer object to access the default form data.
     * Form data will be obtained from QuoteTransfer->getPayment()->getHeidelpaySofort()
     *
     * @return string
     */
    public function getPropertyPath(): string
    {
        return HeidelpayConfig::PAYMENT_METHOD_INVOICE_SECURED_B2C;
    }

    /**
     * Path to the form template
     *
     * @return string
     */
    public function getTemplatePath(): string
    {
        return HeidelpayConfig::PROVIDER_NAME . DIRECTORY_SEPARATOR . static::PAYMENT_METHOD_TEMPLATE_PATH;
    }
}
