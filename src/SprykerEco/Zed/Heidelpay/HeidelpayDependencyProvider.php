<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay;

use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyBridge;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyBridge;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesBridge;
use SprykerEco\Zed\Heidelpay\Dependency\QueryContainer\HeidelpayToSalesQueryContainerBridge;
use SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class HeidelpayDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_CURRENCY = 'currency facade';
    const FACADE_MONEY = 'money facade';
    const FACADE_SALES = 'sales facade';

    const QUERY_CONTAINER_SALES = 'sales query container';

    const SERVICE_UTIL_ENCODING = 'util encoding service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
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
            return new HeidelpayToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_SALES] = function (Container $container) {
            return new HeidelpayToSalesBridge($container->getLocator()->sales()->facade());
        };

        return $container;
    }

}
