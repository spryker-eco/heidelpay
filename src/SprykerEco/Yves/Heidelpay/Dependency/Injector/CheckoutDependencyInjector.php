<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Dependency\Injector;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorInterface;
use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Yves\Heidelpay\Plugin\HeidelpayCreditCardHandlerPlugin;
use SprykerEco\Yves\Heidelpay\Plugin\HeidelpayHandlerPlugin;
use SprykerEco\Yves\Heidelpay\Plugin\Subform\HeidelpayCreditCardSecureSubFormPlugin;
use SprykerEco\Yves\Heidelpay\Plugin\Subform\HeidelpayIdealSubFormPlugin;
use SprykerEco\Yves\Heidelpay\Plugin\Subform\HeidelpayPaypalAuthorizeSubFormPlugin;
use SprykerEco\Yves\Heidelpay\Plugin\Subform\HeidelpayPaypalDebitSubFormPlugin;
use SprykerEco\Yves\Heidelpay\Plugin\Subform\HeidelpaySofortSubFormPlugin;

class CheckoutDependencyInjector implements DependencyInjectorInterface
{
    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface|\Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface|\Spryker\Yves\Kernel\Container
     */
    public function inject(ContainerInterface $container)
    {
        $container = $this->injectPaymentSubForms($container);
        $container = $this->injectPaymentMethodHandler($container);

        return $container;
    }

    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface
     */
    protected function injectPaymentSubForms(ContainerInterface $container)
    {
        $container->extend(CheckoutDependencyProvider::PAYMENT_SUB_FORMS, function (SubFormPluginCollection $paymentSubForms) {
            $paymentSubForms->add(new HeidelpaySofortSubFormPlugin());
            $paymentSubForms->add(new HeidelpayPaypalAuthorizeSubFormPlugin());
            $paymentSubForms->add(new HeidelpayPaypalDebitSubFormPlugin());
            $paymentSubForms->add(new HeidelpayIdealSubFormPlugin());
            $paymentSubForms->add(new HeidelpayCreditCardSecureSubFormPlugin());
            return $paymentSubForms;
        });

        return $container;
    }

    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface
     */
    protected function injectPaymentMethodHandler(ContainerInterface $container)
    {
        $container->extend(CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER, function (StepHandlerPluginCollection $paymentMethodHandler) {
            $paymentMethodHandler->add(new HeidelpayHandlerPlugin(), HeidelpayConfig::PAYMENT_METHOD_SOFORT);
            $paymentMethodHandler->add(new HeidelpayHandlerPlugin(), HeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE);
            $paymentMethodHandler->add(new HeidelpayHandlerPlugin(), HeidelpayConfig::PAYMENT_METHOD_PAYPAL_DEBIT);
            $paymentMethodHandler->add(new HeidelpayHandlerPlugin(), HeidelpayConfig::PAYMENT_METHOD_IDEAL);
            $paymentMethodHandler->add(new HeidelpayCreditCardHandlerPlugin(), HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE);
            return $paymentMethodHandler;
        });

        return $container;
    }
}
