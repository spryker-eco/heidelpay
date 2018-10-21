<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Handler;

use Generated\Shared\Transfer\HeidelpayErrorRedirectResponseTransfer;
use SprykerEco\Client\Heidelpay\HeidelpayClientInterface;
use SprykerEco\Yves\Heidelpay\HeidelpayConfig;

class PaymentFailureHandler implements PaymentFailureHandlerInterface
{
    /**
     * @var \SprykerEco\Client\Heidelpay\HeidelpayClientInterface
     */
    protected $heidelpayClient;

    /**
     * @var \SprykerEco\Yves\Heidelpay\HeidelpayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Client\Heidelpay\HeidelpayClientInterface $heidelpayClient
     * @param \SprykerEco\Yves\Heidelpay\HeidelpayConfig $config
     */
    public function __construct(
        HeidelpayClientInterface $heidelpayClient,
        HeidelpayConfig $config
    ) {
        $this->heidelpayClient = $heidelpayClient;
        $this->config = $config;
    }

    /**
     * @param string $errorCode
     *
     * @return \Generated\Shared\Transfer\HeidelpayErrorRedirectResponseTransfer
     */
    public function handlePaymentFailureByErrorCode($errorCode): HeidelpayErrorRedirectResponseTransfer
    {
        $translatedErrorMessage = $this->getCustomerMessageByErrorCode($errorCode);

        return $this->buildRedirectResponse($translatedErrorMessage);
    }

    /**
     * @param string $errorCode
     *
     * @return string
     */
    protected function getCustomerMessageByErrorCode($errorCode): string
    {
        return $this->heidelpayClient->translateErrorMessageByCode($errorCode);
    }

    /**
     * @param string $translatedErrorMessage
     *
     * @return \Generated\Shared\Transfer\HeidelpayErrorRedirectResponseTransfer
     */
    protected function buildRedirectResponse($translatedErrorMessage): HeidelpayErrorRedirectResponseTransfer
    {
        return (new HeidelpayErrorRedirectResponseTransfer())
            ->setRedirectUrl($this->config->getYvesCheckoutPaymentStepPath())
            ->setErrorMessage($translatedErrorMessage);
    }
}
