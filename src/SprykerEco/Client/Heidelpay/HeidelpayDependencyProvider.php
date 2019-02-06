<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Session\ServiceProvider\SessionClientServiceProvider;
use Spryker\Client\ZedRequest\ServiceProvider\ZedRequestClientServiceProvider;
use SprykerEco\Client\Heidelpay\Dependency\Client\HeidelpayToLocaleClient;
use SprykerEco\Client\Heidelpay\Dependency\Client\HeidelpayToQuoteSessionClient;

class HeidelpayDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_LOCALE = 'client locale';
    public const CLIENT_SESSION = SessionClientServiceProvider::CLIENT_SESSION;
    public const CLIENT_ZED_REQUEST = ZedRequestClientServiceProvider::CLIENT_ZED_REQUEST;
    public const CLIENT_QUOTE = 'client quote';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new HeidelpayToLocaleClient($container->getLocator()->locale()->client());
        };

        $container[static::CLIENT_QUOTE] = function (Container $container) {
            return new HeidelpayToQuoteSessionClient($container->getLocator()->quote()->client());
        };

        $container[static::CLIENT_ZED_REQUEST] = function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        };

        return $container;
    }
}
