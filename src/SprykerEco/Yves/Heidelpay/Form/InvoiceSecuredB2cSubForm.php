<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Form;

use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class InvoiceSecuredB2cSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    protected const PAYMENT_METHOD_TEMPLATE_PATH = 'invoice-secured-b2c';
    protected const FORM_FIELD_DATE_OF_BIRTH = 'dateOfBirth';
    protected const LABEL_DATE_OF_BIRTH = 'payment.heidelpay.date_of_birth_label';
    protected const FORMAT_DATE_OF_BIRTH = 'yyyy-MM-dd';
    protected const PLACEHOLDER_DATE_OF_BIRTH = 'customer.birth_date';
    protected const MIN_BIRTHDAY_DATE_STRING = '-18 years';
    protected const AGE_VIOLATION_MESSAGE = 'checkout.step.payment.must_be_older_than_18_years';
    protected const WIDGET_TYPE = 'single_text';
    protected const INPUT_TYPE = 'string';

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
        return HeidelpayConfig::PAYMENT_METHOD_INVOICE_SECURED_B2C;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return HeidelpayConfig::PAYMENT_METHOD_INVOICE_SECURED_B2C;
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
            'data_class' => HeidelpayPaymentTransfer::class,
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
        $this->addDateOfBirth($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDateOfBirth(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FORM_FIELD_DATE_OF_BIRTH,
            BirthdayType::class,
            [
                'label' => static::LABEL_DATE_OF_BIRTH,
                'required' => true,
                'widget' => static::WIDGET_TYPE,
                'format' => static::FORMAT_DATE_OF_BIRTH,
                'input' => static::INPUT_TYPE,
                'attr' => [
                    'placeholder' => static::PLACEHOLDER_DATE_OF_BIRTH,
                ],

                'constraints' => [
                    $this->createNotBlankConstraint(),
                    $this->createBirthdayConstraint(),
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

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createBirthdayConstraint(): Constraint
    {
        return new Callback([
            'callback' => function ($date, ExecutionContextInterface $context) {
                if (strtotime($date) > strtotime(self::MIN_BIRTHDAY_DATE_STRING)) {
                    $context->addViolation(static::AGE_VIOLATION_MESSAGE);
                }
            },
            'groups' => $this->getPropertyPath(),
        ]);
    }
}
