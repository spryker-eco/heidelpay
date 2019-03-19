<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

class HeidelpayToQuoteClientBridge implements HeidelpayToQuoteClientInterface
{
    /**
     * @var \Spryker\Client\Quote\QuoteClientInterface $quoteClient
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\Quote\QuoteClientInterface $quoteClient
     */
    public function __construct($quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote(): QuoteTransfer
    {
        return $this->quoteClient->getQuote();
    }
}
