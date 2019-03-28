<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Request;

use Generated\Shared\Transfer\HeidelpayAsyncTransfer;
use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use SprykerEco\Zed\Heidelpay\Business\Mapper\OrderToHeidelpayRequestInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReaderInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyFacadeInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayConfig;

class EasyCreditAdapterRequestFromOrderBuilder extends AdapterRequestFromOrderBuilder
{
    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Mapper\OrderToHeidelpayRequestInterface $orderToHeidelpayMapper
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyFacadeInterface $currencyFacade
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfig $config
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReaderInterface $paymentReader
     */
    public function __construct(
        OrderToHeidelpayRequestInterface $orderToHeidelpayMapper,
        HeidelpayToCurrencyFacadeInterface $currencyFacade,
        HeidelpayConfig $config,
        PaymentReaderInterface $paymentReader
    ) {
        parent::__construct(
            $orderToHeidelpayMapper,
            $currencyFacade,
            $config,
            $paymentReader
        );
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
