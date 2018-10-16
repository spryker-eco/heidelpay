<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Basket;

use Generated\Shared\Transfer\HeidelpayBasketRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Basket\BasketInterface;

class BasketHandler implements BasketHandlerInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Adapter\Basket\BasketInterface
     */
    protected $basketAdapter;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Adapter\Basket\BasketInterface $basketAdapter
     */
    public function __construct(BasketInterface $basketAdapter)
    {
        $this->basketAdapter = $basketAdapter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayBasketResponseTransfer
     */
    public function createBasket(QuoteTransfer $quoteTransfer)
    {
        $heidelpayBasketRequestTransfer = (new HeidelpayBasketRequestTransfer())
            ->setItems($quoteTransfer->getItems())
            ->setExpenses($quoteTransfer->getExpenses())
            ->setCurrency($quoteTransfer->getCurrency())
            ->setTotals($quoteTransfer->getTotals());

        return $this->basketAdapter->addNewBasket($heidelpayBasketRequestTransfer);
    }
}
