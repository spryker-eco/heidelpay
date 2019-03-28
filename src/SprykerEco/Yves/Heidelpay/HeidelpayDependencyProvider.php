<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay;

use Spryker\Client\Money\Plugin\MoneyPlugin;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToCalculationClientBridge;
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToPriceClientBridge;
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientBridge;

class HeidelpayDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_HEIDELPAY = 'CLIENT_HEIDELPAY';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const CLIENT_CALCULATION = 'CLIENT_CALCULATION';
    public const CLIENT_PRICE = 'CLIENT_PRICE';
    public const PLUGIN_MONEY = 'PLUGIN_MONEY';

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
        $container = $this->addPriceClient($container);
        $container = $this->addMoneyPlugin($container);

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

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPriceClient(Container $container): Container
    {
        $container[static::CLIENT_PRICE] = function (Container $container) {
            return new HeidelpayToPriceClientBridge($container->getLocator()->price()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container): Container
    {
        $container[static::PLUGIN_MONEY] = function () {
            return new MoneyPlugin();
        };

        return $container;
    }
}
