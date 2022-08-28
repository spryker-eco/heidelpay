<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Form;

use Generated\Shared\Transfer\HeidelpayEasyCreditPaymentTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayConfigInterface getConfig()
 */
class EasyCreditSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    /**
     * @var string
     */
    public const VARS_KEY_LEGAL_TEXT = 'legalText';

    /**
     * @var string
     */
    public const VARS_KEY_LOGO_URL = 'logo_url';

    /**
     * @var string
     */
    public const VARS_KEY_INFO_LINK = 'info_link';

    /**
     * @var string
     */
    protected const FIELD_EASY_CREDIT_POLICY_AGREEMENT = 'isPolicyAgreementChecked';

    /**
     * @var string
     */
    protected const PAYMENT_METHOD_TEMPLATE_PATH = 'easy-credit';

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
        return HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT;
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
            'data_class' => HeidelpayEasyCreditPaymentTransfer::class,
        ])->setRequired(static::OPTIONS_FIELD_NAME);
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
}
