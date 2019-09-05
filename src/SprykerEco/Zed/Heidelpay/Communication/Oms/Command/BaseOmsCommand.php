<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Communication\Oms\Command;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacadeInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeInterface;

class BaseOmsCommand
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\HeidelpayFacadeInterface
     */
    protected $heidelpayFacade;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\HeidelpayFacadeInterface $heidelpayFacade
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        HeidelpayFacadeInterface $heidelpayFacade,
        HeidelpayToSalesFacadeInterface $salesFacade
    ) {
        $this->heidelpayFacade = $heidelpayFacade;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderWithPaymentTransfer(int $idSalesOrder): OrderTransfer
    {
        $orderTransfer = $this->salesFacade->getOrderByIdSalesOrder($idSalesOrder);

        return $this->addHeidelpayPayment($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function addHeidelpayPayment(OrderTransfer $orderTransfer): OrderTransfer
    {
        $paymentTransfer = $this->heidelpayFacade->getPaymentByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $orderTransfer->setHeidelpayPayment($paymentTransfer);

        return $orderTransfer;
    }
}
