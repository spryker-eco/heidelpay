<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper;

use Generated\Shared\Transfer\HeidelpayBasketRequestTransfer;
use Heidelpay\PhpBasketApi\Object\Basket;
use Heidelpay\PhpBasketApi\Object\BasketItem;
use SprykerEco\Zed\Heidelpay\HeidelpayConfigInterface;

class BasketRequestToHeidelpay implements BasketRequestToHeidelpayInterface
{
    public const BASKET_ITEM_GOODS_TYPE = 'goods';
    public const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';
    public const BASKET_ITEM_SHIPPING_TYPE = 'shipping';

    /**
     * @var \SprykerEco\Zed\Heidelpay\HeidelpayConfigInterface
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfigInterface $config
     */
    public function __construct(HeidelpayConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayBasketRequestTransfer $requestTransfer
     * @param \Heidelpay\PhpBasketApi\Object\Basket $heidelpayBasket
     *
     * @return void
     */
    public function map(HeidelpayBasketRequestTransfer $requestTransfer, Basket $heidelpayBasket): void
    {
        $heidelpayBasket
            ->setAmountTotalNet($requestTransfer->getTotals()->getNetTotal())
            ->setAmountTotalVat($requestTransfer->getTotals()->getTaxTotal()->getAmount())
            ->setCurrencyCode($requestTransfer->getCurrency()->getCode());

        $this->mapBasketItems($requestTransfer, $heidelpayBasket);
        $this->mapShipmentItems($requestTransfer, $heidelpayBasket);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayBasketRequestTransfer $requestTransfer
     * @param \Heidelpay\PhpBasketApi\Object\Basket $heidelpayBasket
     *
     * @return void
     */
    protected function mapBasketItems(HeidelpayBasketRequestTransfer $requestTransfer, Basket $heidelpayBasket): void
    {
        $position = 1;
        $isSplitPaymentEnabled = $this->config->getIsSplitPaymentEnabledKey();

        foreach ($requestTransfer->getItems() as $itemTransfer) {
            $basketItem = (new BasketItem())
                ->setPosition($position++)
                ->setBasketItemReferenceId($itemTransfer->getSku() . $position)
                ->setArticleId($itemTransfer->getSku())
                ->setTitle($itemTransfer->getName())
                ->setDescription($itemTransfer->getDescription())
                ->setType(static::BASKET_ITEM_GOODS_TYPE)
                ->setQuantity($itemTransfer->getQuantity())
                ->setVat($itemTransfer->getTaxRate())
                ->setAmountPerUnit($itemTransfer->getUnitPriceToPayAggregation())
                ->setAmountNet($itemTransfer->getSumPriceToPayAggregation() - $itemTransfer->getSumTaxAmount())
                ->setAmountGross($itemTransfer->getSumPriceToPayAggregation())
                ->setAmountVat($itemTransfer->getSumTaxAmountFullAggregation());

            if ($isSplitPaymentEnabled) {
                $basketItem
                    ->setChannel($itemTransfer->getHeidelpayItemChannelId())
                    ->setIsMarketplaceItem($itemTransfer->getHeidelpayItemChannelId() !== null);
            }

            $heidelpayBasket->addBasketItem($basketItem);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayBasketRequestTransfer $requestTransfer
     * @param \Heidelpay\PhpBasketApi\Object\Basket $heidelpayBasket
     *
     * @return void
     */
    protected function mapShipmentItems(HeidelpayBasketRequestTransfer $requestTransfer, Basket $heidelpayBasket): void
    {
        $position = $requestTransfer->getItems()->count() + 1;
        $isSplitPaymentEnabled = $this->config->getIsSplitPaymentEnabledKey();

        foreach ($requestTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== static::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $basketItem = (new BasketItem())
                ->setPosition($position++)
                ->setBasketItemReferenceId($expenseTransfer->getName() . $position)
                ->setArticleId($expenseTransfer->getName())
                ->setChannel($expenseTransfer->getHeidelpayItemChannelId())
                ->setTitle($expenseTransfer->getName())
                ->setType(static::BASKET_ITEM_SHIPPING_TYPE)
                ->setQuantity($expenseTransfer->getQuantity())
                ->setVat($expenseTransfer->getTaxRate())
                ->setAmountPerUnit($expenseTransfer->getUnitPriceToPayAggregation())
                ->setAmountNet(max($expenseTransfer->getSumPriceToPayAggregation() - $expenseTransfer->getSumDiscountAmountAggregation() - $expenseTransfer->getSumTaxAmount(), 0))
                ->setAmountGross($expenseTransfer->getSumPriceToPayAggregation())
                ->setAmountVat($expenseTransfer->getSumTaxAmount());

            if ($isSplitPaymentEnabled) {
                $basketItem
                    ->setChannel($expenseTransfer->getHeidelpayItemChannelId())
                    ->setIsMarketplaceItem($expenseTransfer->getHeidelpayItemChannelId() !== null);
            }

            $heidelpayBasket->addBasketItem($basketItem);
        }
    }
}
