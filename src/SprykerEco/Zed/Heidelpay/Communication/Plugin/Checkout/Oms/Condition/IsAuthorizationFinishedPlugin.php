<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Communication\Plugin\Checkout\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \SprykerEco\Zed\Heidelpay\Business\HeidelpayFacadeInterface getFacade()
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\Heidelpay\Communication\HeidelpayCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\Heidelpay\HeidelpayConfig getConfig()
 */
class IsAuthorizationFinishedPlugin extends AbstractPlugin implements ConditionInterface
{
    /**
     * {@inheritdoc}
     * - Checks if Authorize notification received with success code for given order item.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        return $this->getFactory()
            ->createIsAuthorizationFinishedOmsCondition()
            ->check($orderItem);
    }
}
