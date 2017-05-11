<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heidelpay\Communication;

use Spryker\Zed\Heidelpay\HeidelpayDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Heidelpay\HeidelpayConfig getConfig()
 */
class HeidelpayCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesInterface
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_SALES);
    }

}
