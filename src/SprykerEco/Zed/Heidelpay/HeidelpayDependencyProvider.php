<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyBridge;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyBridge;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesBridge;
use SprykerEco\Zed\Heidelpay\Dependency\QueryContainer\HeidelpayToSalesQueryContainerBridge;
use SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceBridge;

class HeidelpayDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CURRENCY = 'currency facade';
    public const FACADE_MONEY = 'money facade';
    public const FACADE_SALES = 'sales facade';

    public const QUERY_CONTAINER_SALES = 'sales query container';

    public const SERVICE_UTIL_ENCODING = 'util encoding service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container[static::FACADE_CURRENCY] = function (Container $container) {
            return new HeidelpayToCurrencyBridge($container->getLocator()->currency()->facade());
        };

        $container[static::FACADE_MONEY] = function (Container $container) {
            return new HeidelpayToMoneyBridge($container->getLocator()->money()->facade());
        };

        $container[self::FACADE_SALES] = function (Container $container) {
            return new HeidelpayToSalesBridge($container->getLocator()->sales()->facade());
        };

        $container[self::QUERY_CONTAINER_SALES] = function (Container $container) {
            return new HeidelpayToSalesQueryContainerBridge($container->getLocator()->sales()->queryContainer());
        };

        $container[self::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new HeidelpayToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container[self::FACADE_SALES] = function (Container $container) {
            return new HeidelpayToSalesBridge($container->getLocator()->sales()->facade());
        };

        return $container;
    }
}
