<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Heidelpay\Business\DataProviders;

use Functional\SprykerEco\Zed\Heidelpay\Business\DataProviders\Customer\CustomerTrait;
use Functional\SprykerEco\Zed\Heidelpay\Business\DataProviders\Order\NewOrderWithOneItemTrait;
use Functional\SprykerEco\Zed\Heidelpay\Business\DataProviders\Order\OrderAddressTrait;
use Functional\SprykerEco\Zed\Heidelpay\Business\DataProviders\Transaction\AuthorizeTransactionTrait;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;

class OrderWithSuccessfulIdealAuthorizeTransaction
{

    use CustomerTrait, OrderAddressTrait, NewOrderWithOneItemTrait, AuthorizeTransactionTrait;

    /**
     * @return array
     */
    public function createOrderWithIdealAuthorizeTransaction()
    {
        $customerJohnDoe = $this->createOrGetCustomerJohnDoe();
        $billingAddressJohnDoe = $shippingAddressJohnDoe = $this->createOrderAddressJohnDoe();

        $orderEntity = $this->createOrderEntityWithItems(
            $customerJohnDoe,
            $billingAddressJohnDoe,
            $shippingAddressJohnDoe
        );

        $this->createSuccessfulAuthorizeTransactionForOrder($orderEntity);

        $checkoutResponseTransfer = $this->createCheckoutResponseFromOrder($orderEntity);

        $quoteTransfer = $this->createQuoteTransferWithIdealAuthorizePayment($orderEntity);

        return [$quoteTransfer, $checkoutResponseTransfer];
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function buildPaymentTransfer(SpySalesOrder $orderEntity)
    {
        $heidelpayPaymentTransfer = new HeidelpayPaymentTransfer();

        $heidelpayPaymentTransfer
            ->setPaymentMethod(HeidelpayConstants::PAYMENT_METHOD_IDEAL)
            ->setFkSalesOrder($orderEntity->getIdSalesOrder());

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setHeidelpayIdeal($heidelpayPaymentTransfer)
            ->setPaymentMethod(HeidelpayConstants::PAYMENT_METHOD_IDEAL);

        return $paymentTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    private function createQuoteTransferWithIdealAuthorizePayment(
        SpySalesOrder $orderEntity
    ) {
        $paymentTransfer = $this->buildPaymentTransfer($orderEntity);
        $customerTransfer = $this->createCustomerJohnDoeGuestTransfer();

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function createCheckoutResponseFromOrder(SpySalesOrder $orderEntity)
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $saveOrderTransfer = new SaveOrderTransfer();

        $checkoutResponseTransfer->setSaveOrder($saveOrderTransfer);

        $checkoutResponseTransfer->getSaveOrder()->setIdSalesOrder($orderEntity->getIdSalesOrder());

        foreach ($orderEntity->getItems() as $orderItemEntity) {
            $itemTransfer = new ItemTransfer();
            $itemTransfer
                ->setName($orderItemEntity->getName())
                ->setQuantity($orderItemEntity->getQuantity())
                ->setUnitGrossPrice($orderItemEntity->getGrossPrice())
                ->setFkSalesOrder($orderItemEntity->getFkSalesOrder())
                ->setIdSalesOrderItem($orderItemEntity->getIdSalesOrderItem());
            $checkoutResponseTransfer->getSaveOrder()->addOrderItem($itemTransfer);
        }

        return $checkoutResponseTransfer;
    }

}
