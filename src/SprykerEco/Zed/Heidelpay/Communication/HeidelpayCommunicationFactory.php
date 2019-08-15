<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\Heidelpay\Communication\Oms\Command\DebitOnRegistrationOmsCommand;
use SprykerEco\Zed\Heidelpay\Communication\Oms\Command\HeidelpayOmsCommandByOrderInterface;
use SprykerEco\Zed\Heidelpay\Communication\Oms\Command\RefundOmsCommand;
use SprykerEco\Zed\Heidelpay\Communication\Oms\Condition\HeidelpayOmsConditionInterface;
use SprykerEco\Zed\Heidelpay\Communication\Oms\Condition\IsDebitOnRegistrationCompletedOmsCondition;
use SprykerEco\Zed\Heidelpay\Communication\Oms\Condition\IsRefundedOmsCondition;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayDependencyProvider;

/**
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\Heidelpay\HeidelpayConfig getConfig()
 * @method \SprykerEco\Zed\Heidelpay\Business\HeidelpayFacadeInterface getFacade()
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface getRepository()
 */
class HeidelpayCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerEco\Zed\Heidelpay\Communication\Oms\Command\HeidelpayOmsCommandByOrderInterface
     */
    public function createDebitOnRegistrationOmsCommand(): HeidelpayOmsCommandByOrderInterface
    {
        return new DebitOnRegistrationOmsCommand(
            $this->getFacade(),
            $this->getSalesFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Communication\Oms\Command\HeidelpayOmsCommandByOrderInterface
     */
    public function createRefundOmsCommand(): HeidelpayOmsCommandByOrderInterface
    {
        return new RefundOmsCommand(
            $this->getFacade(),
            $this->getSalesFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Communication\Oms\Condition\HeidelpayOmsConditionInterface
     */
    public function createIsDebitOnRegistrationCompletedOmsCondition(): HeidelpayOmsConditionInterface
    {
        return new IsDebitOnRegistrationCompletedOmsCondition($this->getRepository());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Communication\Oms\Condition\HeidelpayOmsConditionInterface
     */
    public function createIsRefundedOmsCondition(): HeidelpayOmsConditionInterface
    {
        return new IsRefundedOmsCondition($this->getRepository());
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeInterface
     */
    public function getSalesFacade(): HeidelpayToSalesFacadeInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::FACADE_SALES);
    }
}
