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

class Saver implements SaverInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithPreSavePaymentInterface[]
     */
    protected $paymentCollection;

    /**
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithPreSavePaymentInterface[] $paymentCollection
     */
    public function __construct(array $paymentCollection)
    {
        $this->paymentCollection = $paymentCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
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
    ) {

        $paymentEntity = $this->buildPaymentEntity($quoteTransfer, $checkoutResponseTransfer);
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
    protected function savePaymentForOrderItem(ItemTransfer $orderItemTransfer, $idPayment)
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
    protected function buildPaymentEntity(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay
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
    ) {

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
     * @return boolean
     */
    protected function hasPaymentMethodSpecificDataToSave($paymentMethodCode)
    {
        return isset($this->paymentCollection[$paymentMethodCode]);
    }

}
