<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Quote;

use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;

trait QuoteMockTrait
{
    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuote(): QuoteTransfer
    {
        $product = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $product->getSku()]);

        $quote = (new QuoteBuilder([CustomerTransfer::EMAIL => 'max@mustermann.de']))
            ->withItem([ItemTransfer::SKU => $product->getSku()])
            ->withCustomer()
            ->withTotals()
            ->withCurrency()
            ->withShippingAddress()
            ->withBillingAddress()
            ->build();

        return $quote;
    }
}
