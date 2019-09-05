<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Form;

use Generated\Shared\Transfer\HeidelpayCreditCardPaymentTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreditCardSecureSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    public const PAYMENT_OPTIONS = 'payment_options';

    protected const FIELD_CREDIT_CARD_PAYMENT_OPTION = 'selected_payment_option';
    protected const PAYMENT_METHOD_TEMPLATE_PATH = 'credit-card-secure';

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
        return HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE;
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return HeidelpayConfig::PROVIDER_NAME . DIRECTORY_SEPARATOR . static::PAYMENT_METHOD_TEMPLATE_PATH;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HeidelpayCreditCardPaymentTransfer::class,
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
                'choices' => $options[static::OPTIONS_FIELD_NAME][static::PAYMENT_OPTIONS],
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
