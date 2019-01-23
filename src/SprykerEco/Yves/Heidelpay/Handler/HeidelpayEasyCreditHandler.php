<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Handler;

use Spryker\Client\Quote\QuoteClientInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;

class HeidelpayEasyCreditHandler extends HeidelpayHandler
{
    public const PAYMENT_PROVIDER = HeidelpayConfig::PROVIDER_NAME;

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
    public function __construct(QuoteClientInterface $quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function addEasyCreditResponseToQuote(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer = parent::addPaymentToQuote($quoteTransfer);
        $this->addCurrentRegistrationToQuote($quoteTransfer);
        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);
        $this->quoteClient->setQuote($quoteTransfer);

        return $quoteTransfer;
    }
}
