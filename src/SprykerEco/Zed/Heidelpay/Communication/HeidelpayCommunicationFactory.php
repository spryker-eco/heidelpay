<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Communication;

use SprykerEco\Zed\Heidelpay\HeidelpayDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\Heidelpay\HeidelpayConfig getConfig()
 */
class HeidelpayCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesInterface
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_SALES);
    }

}
