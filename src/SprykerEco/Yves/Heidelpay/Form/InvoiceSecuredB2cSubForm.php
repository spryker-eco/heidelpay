<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Form;

use DateTime;
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

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayConfig getConfig()
 */
class InvoiceSecuredB2cSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    /**
     * @var string
     */
    protected const PAYMENT_METHOD_TEMPLATE_PATH = 'invoice-secured-b2c';

    /**
     * @var string
     */
    protected const FORM_FIELD_DATE_OF_BIRTH = 'dateOfBirth';

    /**
     * @var string
     */
    protected const LABEL_DATE_OF_BIRTH = 'payment.heidelpay.date_of_birth_label';

    /**
     * @var string
     */
    protected const FORMAT_DATE_OF_BIRTH = 'yyyy-MM-dd';

    /**
     * @var string
     */
    protected const PLACEHOLDER_DATE_OF_BIRTH = 'customer.birth_date';

    /**
     * @var string
     */
    protected const MIN_BIRTHDAY_DATE_STRING = '-18 years';

    /**
     * @var string
     */
    protected const AGE_VIOLATION_MESSAGE = 'checkout.step.payment.must_be_older_than_18_years';

    /**
     * @var string
     */
    protected const WIDGET_TYPE = 'single_text';

    /**
     * @var string
     */
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
     * @param array<mixed> $options
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
            ],
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
                $inputDate = new DateTime($date);
                $minBirthDate = new DateTime(static::MIN_BIRTHDAY_DATE_STRING);
                if ($inputDate > $minBirthDate) {
                    $context->addViolation(static::AGE_VIOLATION_MESSAGE);
                }
            },
            'groups' => $this->getPropertyPath(),
        ]);
    }
}
