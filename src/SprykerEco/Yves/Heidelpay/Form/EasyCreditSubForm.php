<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Form;

use Generated\Shared\Transfer\HeidelpayEasyCreditPaymentTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EasyCreditSubForm extends AbstractHeidelpaySubForm
{
    public const PAYMENT_METHOD = HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT;

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HeidelpayEasyCreditPaymentTransfer::class,
            SubFormInterface::OPTIONS_FIELD_NAME => [],
        ]);
    }
}
