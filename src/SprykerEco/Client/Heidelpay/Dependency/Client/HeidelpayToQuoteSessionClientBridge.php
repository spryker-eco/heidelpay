<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

class HeidelpayToQuoteSessionClientBridge implements HeidelpayToQuoteSessionClientInterface
{
    /**
     * @var \Spryker\Client\Quote\Session\QuoteSessionInterface
     */
    protected $quoteSession;

    /**
     * @param \Spryker\Client\Quote\Session\QuoteSessionInterface $quoteSession
     */
    public function __construct($quoteSession)
    {
        $this->quoteSession = $quoteSession;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote(): QuoteTransfer
    {
        return $this->quoteSession->getQuote();
    }
}
