<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Dependency\Injector;

use Spryker\Zed\Heidelpay\Communication\Plugin\Checkout\Oms\Command\AuthorizePlugin;
use Spryker\Zed\Heidelpay\Communication\Plugin\Checkout\Oms\Command\CapturePlugin;
use Spryker\Zed\Heidelpay\Communication\Plugin\Checkout\Oms\Command\DebitPlugin;
use Spryker\Zed\Heidelpay\Communication\Plugin\Checkout\Oms\Condition\IsAuthorizationCompletedPlugin;
use Spryker\Zed\Heidelpay\Communication\Plugin\Checkout\Oms\Condition\IsCaptureApprovedPlugin;
use Spryker\Zed\Heidelpay\Communication\Plugin\Checkout\Oms\Condition\IsDebitCompletedPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\AbstractDependencyInjector;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\OmsDependencyProvider;

class OmsDependencyInjector extends AbstractDependencyInjector
{

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function injectBusinessLayerDependencies(Container $container)
    {
        $container = $this->injectCommands($container);
        $container = $this->injectConditions($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function injectCommands(Container $container)
    {
        $container->extend(OmsDependencyProvider::COMMAND_PLUGINS, function (CommandCollectionInterface $commandCollection) {
            $commandCollection
                ->add(new AuthorizePlugin(), 'Heidelpay/Authorize')
                ->add(new DebitPlugin(), 'Heidelpay/Debit')
                ->add(new CapturePlugin(), 'Heidelpay/Capture');

            return $commandCollection;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function injectConditions(Container $container)
    {
        $container->extend(OmsDependencyProvider::CONDITION_PLUGINS, function (ConditionCollectionInterface $conditionCollection) {
            $conditionCollection
                ->add(new IsAuthorizationCompletedPlugin(), 'Heidelpay/IsAuthorizationCompleted')
                ->add(new IsDebitCompletedPlugin(), 'Heidelpay/IsDebitCompleted')
                ->add(new IsCaptureApprovedPlugin(), 'Heidelpay/IsCaptureApproved');

            return $conditionCollection;
        });

        return $container;
    }

}
