<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Handler;

use Generated\Shared\Transfer\HeidelpayErrorRedirectResponseTransfer;
use Spryker\Client\Heidelpay\HeidelpayClientInterface;
use Spryker\Yves\Heidelpay\HeidelpayConfig;

class PaymentFailureHandler implements PaymentFailureHandlerInterface
{

    /**
     * @var \Spryker\Client\Heidelpay\HeidelpayClientInterface
     */
    protected $heidelpayClient;

    /**
     * @var \Spryker\Yves\Heidelpay\HeidelpayConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\Heidelpay\HeidelpayClientInterface $heidelpayClient
     * @param \Spryker\Yves\Heidelpay\HeidelpayConfig $config
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
    public function handlePaymentFailureByErrorCode($errorCode)
    {
        $translatedErrorMessage = $this->getCustomerMessageByErrorCode($errorCode);

        return $this->buildRedirectResponse($translatedErrorMessage);
    }

    /**
     * @param string $errorCode
     *
     * @return string
     */
    protected function getCustomerMessageByErrorCode($errorCode)
    {
        return $this->heidelpayClient->translateErrorMessageByCode($errorCode);
    }

    /**
     * @param string $translatedErrorMessage
     *
     * @return \Generated\Shared\Transfer\HeidelpayErrorRedirectResponseTransfer
     */
    protected function buildRedirectResponse($translatedErrorMessage)
    {
        return (new HeidelpayErrorRedirectResponseTransfer())
            ->setRedirectUrl($this->config->getYvesCheckoutPaymentStepPath())
            ->setErrorMessage($translatedErrorMessage);
    }

}
