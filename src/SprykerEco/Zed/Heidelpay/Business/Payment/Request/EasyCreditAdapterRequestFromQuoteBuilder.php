<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Request;

use Generated\Shared\Transfer\HeidelpayAsyncTransfer;
use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use SprykerEco\Zed\Heidelpay\Business\Mapper\QuoteToHeidelpayRequestInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyFacadeInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayConfig;

class EasyCreditAdapterRequestFromQuoteBuilder extends AdapterRequestFromQuoteBuilder
{
    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Mapper\QuoteToHeidelpayRequestInterface $quoteToHeidelpayMapper
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyFacadeInterface $currencyFacade
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfig $config
     */
    public function __construct(
        QuoteToHeidelpayRequestInterface $quoteToHeidelpayMapper,
        HeidelpayToCurrencyFacadeInterface $currencyFacade,
        HeidelpayConfig $config
    ) {
        parent::__construct($quoteToHeidelpayMapper, $currencyFacade, $config);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function hydrateAsyncParameters(HeidelpayRequestTransfer $heidelpayRequestTransfer): HeidelpayRequestTransfer
    {
        $asyncTransfer = (new HeidelpayAsyncTransfer())
            ->setLanguageCode($this->config->getAsyncLanguageCode())
            ->setResponseUrl($this->config->getEasyCreditPaymentResponseUrl());

        $heidelpayRequestTransfer->setAsync($asyncTransfer);

        return $heidelpayRequestTransfer;
    }
}
