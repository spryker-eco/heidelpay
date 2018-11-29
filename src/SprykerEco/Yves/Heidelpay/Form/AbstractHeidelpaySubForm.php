<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Form;

use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractHeidelpaySubForm extends AbstractSubFormType implements SubFormInterface
{
    public const PAYMENT_PROVIDER = HeidelpayConfig::PROVIDER_NAME;
    public const PAYMENT_METHOD = '';
    public const PAYMENT_METHOD_TEMPLATE_PATH = '';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HeidelpayPaymentTransfer::class,
            SubFormInterface::OPTIONS_FIELD_NAME => [],
        ]);
    }

    /**
     * Specifies the property name of the payment transfer object to access the default form data.
     * Form data will be obtained from QuoteTransfer->getPayment()->getHeidelpaySofort()
     *
     * @return string
     */
    public function getPropertyPath(): string
    {
        return static::PAYMENT_METHOD;
    }

    /**
     * Using this key, the subform will be registered inside of the twig provider and will be called
     * in payment.twig template.
     *
     * @return string
     */
    public function getName(): string
    {
        return static::PAYMENT_METHOD;
    }

    /**
     * Path to the form template
     *
     * @return string
     */
    public function getTemplatePath(): string
    {
        return static::PAYMENT_PROVIDER . '/' . static::PAYMENT_METHOD_TEMPLATE_PATH;
    }
}
