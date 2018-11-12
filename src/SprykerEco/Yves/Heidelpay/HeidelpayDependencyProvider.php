<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

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
        $container = $this->provideClients($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideClients(Container $container): Container
    {
        $container[static::CLIENT_HEIDELPAY] = function (Container $container) {
            return $container->getLocator()->heidelpay()->client();
        };

        $container[static::CLIENT_QUOTE] = function (Container $container) {
            return $container->getLocator()->quote()->client();
        };

        $container[static::CLIENT_CALCULATION] = function (Container $container) {
            return $container->getLocator()->calculation()->client();
        };

        return $container;
    }
}
