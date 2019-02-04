<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayDependencyProvider;

/**
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\Heidelpay\HeidelpayConfig getConfig()
 * @method \SprykerEco\Zed\Heidelpay\Business\HeidelpayFacadeInterface getFacade()
 */
class HeidelpayCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesInterface
     */
    public function getSalesFacade(): HeidelpayToSalesInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_SALES);
    }
}
