<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Dependency\Client;

class HeidelpayToPriceClientBridge implements HeidelpayToPriceClientInterface
{
    /**
     * @var \Spryker\Client\Price\PriceClientInterface
     */
    protected $priceClient;

    /**
     * @param \Spryker\Client\Price\PriceClientInterface $priceClient
     */
    public function __construct($priceClient)
    {
        $this->priceClient = $priceClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getNetPriceModeIdentifier()
    {
        return $this->priceClient->getNetPriceModeIdentifier();
    }
}
