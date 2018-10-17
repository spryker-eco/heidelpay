<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\Heidelpay\HeidelpayDependencyProvider;

/**
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\Heidelpay\HeidelpayConfig getConfig()
 */
class HeidelpayCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return mixed
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_SALES);
    }
}
