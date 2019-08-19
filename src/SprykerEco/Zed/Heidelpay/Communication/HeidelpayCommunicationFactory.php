<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\Heidelpay\Communication\Oms\Condition\HeidelpayOmsConditionInterface;
use SprykerEco\Zed\Heidelpay\Communication\Oms\Condition\IsPaidNotificationReceivedOmsCondition;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayDependencyProvider;

/**
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\Heidelpay\HeidelpayConfig getConfig()
 * @method \SprykerEco\Zed\Heidelpay\Business\HeidelpayFacadeInterface getFacade()
 */
class HeidelpayCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerEco\Zed\Heidelpay\Communication\Oms\Condition\HeidelpayOmsConditionInterface
     */
    public function createIsPaidNotificationReceivedOmsCondition(): HeidelpayOmsConditionInterface
    {
        return new IsPaidNotificationReceivedOmsCondition($this->getRepository());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeInterface
     */
    public function getSalesFacade(): HeidelpayToSalesFacadeInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_SALES);
    }
}
