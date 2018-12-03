<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Basket;

use Generated\Shared\Transfer\HeidelpayBasketRequestTransfer;
use Generated\Shared\Transfer\HeidelpayBasketResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Basket\BasketInterface;

class BasketCreator implements BasketCreatorInterface
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
    public function createBasket(QuoteTransfer $quoteTransfer): HeidelpayBasketResponseTransfer
    {
        $heidelpayBasketRequestTransfer = (new HeidelpayBasketRequestTransfer())
            ->setItems($quoteTransfer->getItems())
            ->setExpenses($quoteTransfer->getExpenses())
            ->setCurrency($quoteTransfer->getCurrency())
            ->setTotals($quoteTransfer->getTotals());

        return $this->basketAdapter->addNewBasket($heidelpayBasketRequestTransfer);
    }
}
