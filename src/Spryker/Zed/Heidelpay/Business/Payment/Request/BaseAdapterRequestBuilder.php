<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heidelpay\Business\Payment\Request;

use Generated\Shared\Transfer\HeidelpayAsyncTransfer;
use Generated\Shared\Transfer\HeidelpayAuthenticationTransfer;
use Generated\Shared\Transfer\HeidelpayRequestTransfer;

class BaseAdapterRequestBuilder
{

    /**
     * @var \Spryker\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\Heidelpay\HeidelpayConfig
     */
    protected $config;

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function hydrateAuthenticationInfo(HeidelpayRequestTransfer $heidelpayRequestTransfer)
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
    protected function hydrateAsyncParameters(HeidelpayRequestTransfer $heidelpayRequestTransfer)
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
    protected function hydrateApplicationSecret(HeidelpayRequestTransfer $heidelpayRequestTransfer)
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
    protected function hydrateTransactionChannel(HeidelpayRequestTransfer $heidelpayRequestTransfer, $paymentMethod)
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
    protected function hydrateCurrency(HeidelpayRequestTransfer $heidelpayRequestTransfer)
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
    protected function hydrateRequestData(HeidelpayRequestTransfer $requestTransfer)
    {
        $requestTransfer = $this->hydrateAuthenticationInfo($requestTransfer);
        $requestTransfer = $this->hydrateApplicationSecret($requestTransfer);
        $requestTransfer = $this->hydrateAsyncParameters($requestTransfer);
        $requestTransfer = $this->hydrateCurrency($requestTransfer);

        return $requestTransfer;
    }

}
