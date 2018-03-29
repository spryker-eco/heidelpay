<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Form;

use Generated\Shared\Transfer\HeidelpayCreditCardPaymentTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreditCardSecureSubForm extends AbstractHeidelpaySubForm
{
    const PAYMENT_METHOD = HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE;

    const PAYMENT_OPTIONS = 'payment_options';
    const PAYMENT_OPTION_EXISTING_REGISTRATION = HeidelpayConfig::PAYMENT_OPTION_EXISTING_REGISTRATION;
    const PAYMENT_OPTION_NEW_REGISTRATION = HeidelpayConfig::PAYMENT_OPTION_NEW_REGISTRATION;

    const FIELD_CREDIT_CARD_PAYMENT_OPTION = 'selected_payment_option';
    const FIELD_CREDIT_CARD_REGISTRATION_ID = 'registration_id';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HeidelpayCreditCardPaymentTransfer::class,
            SubFormInterface::OPTIONS_FIELD_NAME => [],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCreditCardPaymentOptions($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function addCreditCardPaymentOptions(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_CREDIT_CARD_PAYMENT_OPTION,
            ChoiceType::class,
            [
                'choices' => $options['select_options'][self::PAYMENT_OPTIONS],
                'label' => false,
                'required' => true,
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param array $options
     *
     * @return bool
     */
    protected function hasExistingRegistrationOption(array $options)
    {
        $paymentOptions = $options['select_options'][static::PAYMENT_OPTIONS];

        return isset($paymentOptions[static::PAYMENT_OPTION_EXISTING_REGISTRATION]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createNotBlankConstraint()
    {
        return new NotBlank(['groups' => $this->getPropertyPath()]);
    }
}
