<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Form;

use Generated\Shared\Transfer\HeidelpayEasyCreditPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

class EasyCreditSubForm extends AbstractHeidelpaySubForm
{
    public const PAYMENT_METHOD = HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT;
    public const PAYMENT_METHOD_TEMPLATE_PATH = 'easy-credit';
    public const VARS_KEY_LEGAL_TEXT = 'legalText';
    public const VARS_KEY_LOGO_URL = 'logo_url';
    public const VARS_KEY_INFO_LINK = 'info_link';

    protected const FIELD_EASY_CREDIT_POLICY_AGREEMENT = 'privacy_policy_status';


    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HeidelpayEasyCreditPaymentTransfer::class,
            SubFormInterface::OPTIONS_FIELD_NAME => [],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);

        $view->vars[static::VARS_KEY_LOGO_URL] = $options[static::OPTIONS_FIELD_NAME][static::VARS_KEY_LOGO_URL] ?? '';
        $view->vars[static::VARS_KEY_INFO_LINK] = $options[static::OPTIONS_FIELD_NAME][static::VARS_KEY_INFO_LINK] ?? '';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addEasyCreditPaymentAgreement($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function addEasyCreditPaymentAgreement(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_EASY_CREDIT_POLICY_AGREEMENT,
            CheckboxType::class,
            [
                'label' => $options[static::OPTIONS_FIELD_NAME][static::VARS_KEY_LEGAL_TEXT] ?? '',
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
