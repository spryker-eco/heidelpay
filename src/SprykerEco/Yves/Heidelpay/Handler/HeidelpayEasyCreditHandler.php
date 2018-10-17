<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Handler;

use Spryker\Client\Quote\QuoteClientInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;

class HeidelpayEasyCreditHandler extends HeidelpayHandler
{
    const PAYMENT_PROVIDER = HeidelpayConfig::PROVIDER_NAME;

    /**
     * @var array
     */
    protected $heidelpayEasyCreditResponse;

    /**
     * @var \Spryker\Client\Quote\QuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\Quote\QuoteClientInterface $quoteClient
     */
    public function __construct(
        QuoteClientInterface $quoteClient
    ) {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addEasyCreditResponseToQuote(AbstractTransfer $quoteTransfer)
    {
        $quoteTransfer = parent::addPaymentToQuote($quoteTransfer);
        $this->quoteClient->setQuote($quoteTransfer);
        return $quoteTransfer;
    }
}
