<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper;

use Generated\Shared\Transfer\HeidelpayBasketRequestTransfer;
use Heidelpay\PhpBasketApi\Object\Basket;
use Heidelpay\PhpBasketApi\Object\BasketItem;

class BasketRequestToHeidelpay implements BasketRequestToHeidelpayInterface
{
    public const BASKET_ITEM_GOODS_TYPE = 'goods';

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
            ->setAmountTotalDiscount($requestTransfer->getTotals()->getDiscountTotal())
            ->setCurrencyCode($requestTransfer->getCurrency()->getCode());

        $this->mapBasketItems($requestTransfer, $heidelpayBasket);
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
        foreach ($requestTransfer->getItems() as $itemTransfer) {
            $basketItem = (new BasketItem())
                ->setPosition($position++)
                ->setBasketItemReferenceId($itemTransfer->getSku())
                ->setChannel($itemTransfer->getHeidelpayItemChannelId())
                ->setArticleId($itemTransfer->getSku())
                ->setTitle($itemTransfer->getName())
                ->setDescription($itemTransfer->getDescription())
                ->setType(static::BASKET_ITEM_GOODS_TYPE)
                ->setQuantity($itemTransfer->getQuantity())
                ->setVat($itemTransfer->getTaxRate())
                ->setAmountPerUnit($itemTransfer->getUnitGrossPrice())
                ->setAmountNet($itemTransfer->getUnitNetPrice())
                ->setAmountGross($itemTransfer->getSumGrossPrice())
                ->setAmountVat($itemTransfer->getSumTaxAmountFullAggregation())
                ->setAmountDiscount($itemTransfer->getSumDiscountAmountFullAggregation());

            $heidelpayBasket->addBasketItem($basketItem);
        }
    }
}
