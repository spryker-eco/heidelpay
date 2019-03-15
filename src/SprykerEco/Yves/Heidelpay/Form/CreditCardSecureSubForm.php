<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Form;

use Generated\Shared\Transfer\HeidelpayCreditCardPaymentTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreditCardSecureSubForm extends AbstractHeidelpaySubForm
{
    public const PAYMENT_METHOD = HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE;

    public const PAYMENT_OPTIONS = 'payment_options';
    public const PAYMENT_OPTION_EXISTING_REGISTRATION = HeidelpayConfig::PAYMENT_OPTION_EXISTING_REGISTRATION;
    public const PAYMENT_OPTION_NEW_REGISTRATION = HeidelpayConfig::PAYMENT_OPTION_NEW_REGISTRATION;

    public const FIELD_CREDIT_CARD_PAYMENT_OPTION = 'selected_payment_option';
    public const FIELD_CREDIT_CARD_REGISTRATION_ID = 'registration_id';

    public const PAYMENT_METHOD_TEMPLATE_PATH = 'credit-card-secure';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
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
    public function buildForm(FormBuilderInterface $builder, array $options): void
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
    protected function hasExistingRegistrationOption(array $options): bool
    {
        $paymentOptions = $options['select_options'][static::PAYMENT_OPTIONS];

        return isset($paymentOptions[static::PAYMENT_OPTION_EXISTING_REGISTRATION]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createNotBlankConstraint(): Constraint
    {
        return new NotBlank(['groups' => $this->getPropertyPath()]);
    }
}
