<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Heidelpay\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

class HeidelpayToQuoteBridge implements HeidelpayToQuoteInterface
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
