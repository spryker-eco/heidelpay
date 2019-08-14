<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Form;

use Generated\Shared\Transfer\HeidelpayDirectDebitPaymentTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

class DirectDebitSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    public const DIRECT_DEBIT_PAYMENT_OPTIONS = 'direct_debit_payment_options';

    protected const PAYMENT_METHOD_TEMPLATE_PATH = 'direct-debit';
    protected const FIELD_DIRECT_DEBIT_PAYMENT_OPTION = 'selected_payment_option';

    /**
     * @return string
     */
    public function getProviderName(): string
    {
        return HeidelpayConfig::PROVIDER_NAME;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return HeidelpayConfig::PAYMENT_METHOD_DIRECT_DEBIT;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return HeidelpayConfig::PAYMENT_METHOD_DIRECT_DEBIT;
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return HeidelpayConfig::PROVIDER_NAME . DS . static::PAYMENT_METHOD_TEMPLATE_PATH;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HeidelpayDirectDebitPaymentTransfer::class,
        ])->setRequired(static::OPTIONS_FIELD_NAME);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addRegistrationSelectField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addRegistrationSelectField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_DIRECT_DEBIT_PAYMENT_OPTION,
            ChoiceType::class,
            [
                'choices' => array_flip($options[static::OPTIONS_FIELD_NAME][static::DIRECT_DEBIT_PAYMENT_OPTIONS]),
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
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createNotBlankConstraint(): Constraint
    {
        return new NotBlank(['groups' => $this->getPropertyPath()]);
    }
}
