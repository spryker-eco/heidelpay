<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToCalculationClientBridge;
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientBridge;

class HeidelpayDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_HEIDELPAY = 'heidelpay client';
    public const CLIENT_QUOTE = 'quote client';
    public const CLIENT_CALCULATION = 'calculation client';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addHeidelpayClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addCalculationClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addHeidelpayClient(Container $container): Container
    {
        $container[static::CLIENT_HEIDELPAY] = function (Container $container) {
            return $container->getLocator()->heidelpay()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container[static::CLIENT_QUOTE] = function (Container $container) {
            return new HeidelpayToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCalculationClient(Container $container): Container
    {
        $container[static::CLIENT_CALCULATION] = function (Container $container) {
            return new HeidelpayToCalculationClientBridge($container->getLocator()->calculation()->client());
        };

        return $container;
    }
}
