<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Heidelpay\PhpBasketApi\Object\Authentication;
use Heidelpay\PhpBasketApi\Object\Basket;
use Heidelpay\PhpBasketApi\Object\BasketItem;
use Heidelpay\PhpBasketApi\Request;
use Heidelpay\PhpBasketApi\Response;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayOrderItem;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface;

class Saver implements SaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var string constant for test login user
     */
    const AUTH_LOGIN = '31ha07bc8142c5a171744e5aef11ffd3';

    /**
     * @var string constant for test login password
     */
    const AUTH_PASSWORD = '93167DE7';

    /**
     * @var string constant for test sender id
     */
    const AUTH_SENDER_ID = '31HA07BC8142C5A171745D00AD63D182';

    /**
     * @var Authentication the authentication object for all requests
     */
    protected $auth;

    /**
     * @var Basket the basket object for all requests
     */
    protected $basket;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPreSavePaymentInterface[]
     */
    protected $paymentCollection;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPreSavePaymentInterface[] $paymentCollection
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
     * @param string $paymentMethod
     *
     * @return boolean
     */
    protected function getBasket(QuoteTransfer $quoteTransfer)
    {
        // set the Authentication
        $this->auth = new Authentication(self::AUTH_LOGIN, self::AUTH_PASSWORD, self::AUTH_SENDER_ID);

        // set up a basket
        $this->basket = new Basket();
        $this->basket->setAmountTotalNet(8192);
        $this->basket->setAmountTotalVat(1557);
        $this->basket->setAmountTotalDiscount(0);
        $this->basket->setCurrencyCode('EUR');
        $this->basket->setBasketReferenceId('heidelpay-php-basket-api-integration-test');
        $this->basket->setNote('heidelpay php-basket-api test basket');

        // set up a first basket item
        $basketItemOne = new BasketItem();
        $basketItemOne->setPosition(1);
        $basketItemOne->setBasketItemReferenceId('heidelpay-php-basket-api-testitem-1');
        $basketItemOne->setUnit('Stk.');
        $basketItemOne->setArticleId('heidelpay-testitem-1');
        $basketItemOne->setTitle('Heidelpay Test Article #1');
        $basketItemOne->setDescription('Just for testing.');
        $basketItemOne->setType('goods');
        $basketItemOne->setImageUrl('https://placehold.it/223302316.jpg');
        $basketItemOne->setQuantity(1);
        $basketItemOne->setVat(19);
        $basketItemOne->setAmountPerUnit(1000);
        $basketItemOne->setAmountNet(840);
        $basketItemOne->setAmountGross(1000);
        $basketItemOne->setAmountVat(160);
        $basketItemOne->setAmountDiscount(0);

        // set up a second basket item
        $basketItemTwo = new BasketItem();
        $basketItemTwo->setPosition(2);
        $basketItemTwo->setBasketItemReferenceId('heidelpay-php-basket-api-testitem-2');
        $basketItemTwo->setUnit('Stk.');
        $basketItemTwo->setArticleId('heidelpay-testitem-2');
        $basketItemTwo->setTitle('Heidelpay Test Article #2');
        $basketItemTwo->setDescription('Just for testing.');
        $basketItemTwo->setType('goods');
        $basketItemTwo->setImageUrl('https://placehold.it/236566083.jpg');
        $basketItemTwo->setQuantity(1);
        $basketItemTwo->setVat(19);
        $basketItemTwo->setAmountPerUnit(7999);
        $basketItemTwo->setAmountNet(6722);
        $basketItemTwo->setAmountGross(7999);
        $basketItemTwo->setAmountVat(1277);
        $basketItemTwo->setAmountDiscount(0);

        // set up a third basket item (shipping)
        $basketItemThree = new BasketItem();
        $basketItemThree->setPosition(3);
        $basketItemThree->setBasketItemReferenceId('heidelpay-php-basket-api-testitem-3');
        $basketItemThree->setUnit('Stk.');
        $basketItemThree->setArticleId('heidelpay-testitem-3');
        $basketItemThree->setTitle('Heidelpay Test Article #3');
        $basketItemThree->setDescription('Just for testing.');
        $basketItemThree->setType('goods');
        $basketItemThree->setQuantity(1);
        $basketItemThree->setVat(19);
        $basketItemThree->setAmountPerUnit(750);
        $basketItemThree->setAmountNet(630);
        $basketItemThree->setAmountGross(750);
        $basketItemThree->setAmountVat(120);
        $basketItemThree->setAmountDiscount(0);

        $this->basket->addBasketItem($basketItemOne);
        $this->basket->addBasketItem($basketItemTwo);
        $this->basket->addBasketItem($basketItemThree);

        $request = new Request($this->auth, $this->basket);
        $response = $request->addNewBasket();

        return $response;
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
        $basket = $this->getBasket($quoteTransfer);
        $basketId = $basket->getBasketId();
        $paymentEntity->setIdBasket($basketId);
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
