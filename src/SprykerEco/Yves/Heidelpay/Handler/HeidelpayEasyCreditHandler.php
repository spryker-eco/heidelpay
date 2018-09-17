<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Handler;

use Spryker\Client\Calculation\CalculationClientInterface;
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
     * @param array $heidelpayEasyCreditResponse
     * @param \Spryker\Client\Quote\QuoteClientInterface $quoteClient
     */
    public function __construct(
        QuoteClientInterface $quoteClient
    ) {
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
