<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Form;

use Generated\Shared\Transfer\HeidelpayEasyCreditPaymentTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;

class EasyCreditSubForm extends AbstractHeidelpaySubForm
{
    const PAYMENT_METHOD = HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT;

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
