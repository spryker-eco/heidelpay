<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Request;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\HeidelpayAsyncTransfer;
use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use SprykerEco\Zed\Heidelpay\Business\Mapper\QuoteToHeidelpayRequestInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyFacadeInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayConfig;

class EasyCreditAdapterRequestFromQuoteBuilder extends AdapterRequestFromQuoteBuilder
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Mapper\QuoteToHeidelpayRequestInterface $quoteToHeidelpayMapper
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyFacadeInterface $currencyFacade
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfig $config
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        QuoteToHeidelpayRequestInterface $quoteToHeidelpayMapper,
        HeidelpayToCurrencyFacadeInterface $currencyFacade,
        HeidelpayConfig $config,
        HeidelpayToSalesFacadeInterface $salesFacade

    ) {
        parent::__construct($quoteToHeidelpayMapper, $currencyFacade, $config);
        $this->salesFacade = $salesFacade;
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

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function hydrateRequestData(HeidelpayRequestTransfer $requestTransfer): HeidelpayRequestTransfer
    {
        $requestTransfer = $this->hydrateAuthenticationInfo($requestTransfer);
        $requestTransfer = $this->hydrateApplicationSecret($requestTransfer);
        $requestTransfer = $this->hydrateAsyncParameters($requestTransfer);
        $requestTransfer = $this->hydrateCurrency($requestTransfer);
        $requestTransfer = $this->hydrateRiskInformation($requestTransfer);

        return $requestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function hydrateRiskInformation(HeidelpayRequestTransfer $heidelpayRequestTransfer): HeidelpayRequestTransfer
    {
        if ($heidelpayRequestTransfer->getRiskInformation()->getIsCustomerGuest()) {
            return $heidelpayRequestTransfer;
        }

        $orderListTransfer = $this->salesFacade->getCustomerOrders(
            new OrderListTransfer(),
            $heidelpayRequestTransfer->getRiskInformation()->getCustomerId()
        );

        $heidelpayRequestTransfer->getRiskInformation()->setCustomerOrdersCount($orderListTransfer->getOrders()->count());

        return $heidelpayRequestTransfer;
    }
}
