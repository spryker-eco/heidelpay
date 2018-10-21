<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayOrderItem;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use SprykerEco\Zed\Heidelpay\Business\Basket\BasketCreatorInterface;

class Saver implements SaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Basket\BasketCreatorInterface
     */
    protected $basketCreator;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPreSavePaymentInterface[]
     */
    protected $paymentCollection;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Basket\BasketCreatorInterface $basketCreator
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPreSavePaymentInterface[] $paymentCollection
     */
    public function __construct(BasketCreatorInterface $basketCreator, array $paymentCollection)
    {
        $this->basketCreator = $basketCreator;
        $this->paymentCollection = $paymentCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        $this->handleDatabaseTransaction(function () use ($quoteTransfer, $checkoutResponseTransfer) {
            $this->executeSavePaymentForOrderAndItemsTransaction($quoteTransfer, $checkoutResponseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function executeSavePaymentForOrderAndItemsTransaction(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void {

        $paymentEntity = $this->buildPaymentEntity($quoteTransfer, $checkoutResponseTransfer);
        $this->addBasketInformation($quoteTransfer, $paymentEntity);

        $paymentEntity->save();

        $idPayment = $paymentEntity->getIdPaymentHeidelpay();

        foreach ($checkoutResponseTransfer->getSaveOrder()->getOrderItems() as $orderItem) {
            $this->savePaymentForOrderItem($orderItem, $idPayment);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $orderItemTransfer
     * @param int $idPayment
     *
     * @return void
     */
    protected function savePaymentForOrderItem(ItemTransfer $orderItemTransfer, $idPayment): void
    {
        $paymentOrderItemEntity = new SpyPaymentHeidelpayOrderItem();
        $paymentOrderItemEntity
            ->setFkPaymentHeidelpay($idPayment)
            ->setFkSalesOrderItem($orderItemTransfer->getIdSalesOrderItem());
        $paymentOrderItemEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay
     */
    protected function buildPaymentEntity(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): SpyPaymentHeidelpay
    {
        $paymentEntity = new SpyPaymentHeidelpay();
        $paymentEntity
            ->setPaymentMethod($quoteTransfer->getPayment()->getPaymentMethod())
            ->setFkSalesOrder($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder());

        $this->hydratePaymentMethodSpecificDataToPayment($paymentEntity, $quoteTransfer);

        return $paymentEntity;
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay $paymentEntity
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function hydratePaymentMethodSpecificDataToPayment(
        SpyPaymentHeidelpay $paymentEntity,
        QuoteTransfer $quoteTransfer
    ): void {

        $paymentMethodCode = $quoteTransfer->getPayment()->getPaymentMethod();

        if (!$this->hasPaymentMethodSpecificDataToSave($paymentMethodCode)) {
            return;
        }

        $paymentMethod = $this->paymentCollection[$paymentMethodCode];
        $paymentMethod->addDataToPayment($paymentEntity, $quoteTransfer);
    }

    /**
     * @param string $paymentMethodCode
     *
     * @return bool
     */
    protected function hasPaymentMethodSpecificDataToSave($paymentMethodCode): bool
    {
        return isset($this->paymentCollection[$paymentMethodCode]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay $paymentEntity
     *
     * @return void
     */
    protected function addBasketInformation(QuoteTransfer $quoteTransfer, SpyPaymentHeidelpay $paymentEntity): void
    {
        $heidelpayBasketResponseTransfer = $this->basketCreator
            ->createBasket($quoteTransfer);

        $paymentEntity->setIdBasket($heidelpayBasketResponseTransfer->getBasketId());
    }
}
