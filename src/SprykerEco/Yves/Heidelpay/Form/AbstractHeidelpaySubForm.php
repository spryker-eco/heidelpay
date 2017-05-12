<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Form;

use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Spryker\Shared\Heidelpay\HeidelpayConstants;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractHeidelpaySubForm extends AbstractSubFormType implements SubFormInterface
{

    /**
     * @const string
     */
    const PAYMENT_PROVIDER = HeidelpayConstants::PROVIDER_NAME;

    /**
     * @const string
     */
    const PAYMENT_METHOD = '';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
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
    public function getPropertyPath()
    {
        return static::PAYMENT_METHOD;
    }

    /**
     * Using this key, the subform will be registered inside of the twig provider and will be called
     * in payment.twig template.
     *
     * @return string
     */
    public function getName()
    {
        return static::PAYMENT_METHOD;
    }

    /**
     * Path to the form template
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return static::PAYMENT_PROVIDER . '/' . static::PAYMENT_METHOD;
    }

}
