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
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayConfigInterface getConfig()
 */
class PaypalAuthorizeSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    /**
     * @var string
     */
    protected const PAYMENT_METHOD_TEMPLATE_PATH = 'paypal-authorize';

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
        return HeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return HeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE;
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
}
