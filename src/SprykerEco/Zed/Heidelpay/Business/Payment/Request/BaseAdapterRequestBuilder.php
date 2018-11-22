<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Request;

use Generated\Shared\Transfer\HeidelpayAsyncTransfer;
use Generated\Shared\Transfer\HeidelpayAuthenticationTransfer;
use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayConfig;

class BaseAdapterRequestBuilder
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var \SprykerEco\Zed\Heidelpay\HeidelpayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyInterface $currencyFacade
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfig $config
     */
    public function __construct(
        HeidelpayToCurrencyInterface $currencyFacade,
        HeidelpayConfig $config
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function hydrateAuthenticationInfo(HeidelpayRequestTransfer $heidelpayRequestTransfer): HeidelpayRequestTransfer
    {
        $authenticationTransfer = (new HeidelpayAuthenticationTransfer())
            ->setUserLogin($this->config->getMerchantUserLogin())
            ->setUserPassword($this->config->getMerchantUserPassword())
            ->setSecuritySender($this->config->getMerchantSecuritySender())
            ->setIsSandboxRequest($this->config->getMerchantSandboxMode());

        $heidelpayRequestTransfer->setAuth($authenticationTransfer);

        return $heidelpayRequestTransfer;
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
            ->setResponseUrl($this->config->getZedResponseUrl());

        $heidelpayRequestTransfer->setAsync($asyncTransfer);

        return $heidelpayRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function hydrateApplicationSecret(HeidelpayRequestTransfer $heidelpayRequestTransfer): HeidelpayRequestTransfer
    {
        $heidelpayRequestTransfer->getCustomerPurchase()
            ->setSecret($this->config->getApplicationSecret());

        return $heidelpayRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     * @param string $paymentMethod
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function hydrateTransactionChannel(HeidelpayRequestTransfer $heidelpayRequestTransfer, $paymentMethod): HeidelpayRequestTransfer
    {
        $transactionChannel = $this->config->getMerchantTransactionChannelByPaymentType($paymentMethod);
        $heidelpayRequestTransfer->getAuth()->setTransactionChannel($transactionChannel);

        return $heidelpayRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function hydrateCurrency(HeidelpayRequestTransfer $heidelpayRequestTransfer): HeidelpayRequestTransfer
    {
        $currencyCode = $this->currencyFacade->getCurrent()->getCode();
        $heidelpayRequestTransfer->getCustomerPurchase()
            ->setCurrencyCode($currencyCode);

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

        return $requestTransfer;
    }
}
