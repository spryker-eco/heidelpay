<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyFacadeBridge;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyFacadeBridge;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeBridge;
use SprykerEco\Zed\Heidelpay\Dependency\QueryContainer\HeidelpayToSalesQueryContainerBridge;
use SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceBridge;

class HeidelpayDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CURRENCY = 'currency facade';
    public const FACADE_MONEY = 'money facade';
    public const FACADE_SALES = 'sales facade';

    public const QUERY_CONTAINER_SALES = 'sales query container';

    public const SERVICE_UTIL_ENCODING = 'util encoding service';

    public const PLUGIN_HEIDELPAY_NOTIFICATION_EXPANDER = 'PLUGIN_HEIDELPAY_NOTIFICATION_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCurrencyFacade($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addSalesQueryContainer($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addHeidelpayNotificationExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addSalesFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container[static::FACADE_SALES] = function (Container $container) {
            return new HeidelpayToSalesFacadeBridge($container->getLocator()->sales()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container): Container
    {
        $container[static::FACADE_CURRENCY] = function (Container $container) {
            return new HeidelpayToCurrencyFacadeBridge($container->getLocator()->currency()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container): Container
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            return new HeidelpayToMoneyFacadeBridge($container->getLocator()->money()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesQueryContainer(Container $container): Container
    {
        $container[static::QUERY_CONTAINER_SALES] = function (Container $container) {
            return new HeidelpayToSalesQueryContainerBridge($container->getLocator()->sales()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new HeidelpayToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addHeidelpayNotificationExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGIN_HEIDELPAY_NOTIFICATION_EXPANDER] = function () {
            return $this->getHeidelpayNotificationExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Plugin\HeidelpayNotificationExpanderPluginInterface[]
     */
    protected function getHeidelpayNotificationExpanderPlugins(): array
    {
        return [];
    }
}
