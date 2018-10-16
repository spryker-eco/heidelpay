<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig as SharedHeidelpayConfig;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;

class HeidelpayConfig extends AbstractBundleConfig implements HeidelpayConfigInterface
{
    public const MERCHANT_TRANSACTION_CONFIG_NOT_FOUND = '';

    /**
     * @return string
     */
    public function getMerchantSecuritySender()
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_SECURITY_SENDER);
    }

    /**
     * @return string
     */
    public function getMerchantUserLogin()
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_USER_LOGIN);
    }

    /**
     * @return string
     */
    public function getMerchantUserPassword()
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_USER_PASSWORD);
    }

    /**
     * @return bool
     */
    public function getMerchantSandboxMode()
    {
        return (bool)$this->get(HeidelpayConstants::CONFIG_HEIDELPAY_SANDBOX_REQUEST);
    }

    /**
     * @return string
     */
    public function getApplicationSecret()
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_APPLICATION_SECRET);
    }

    /**
     * @return string
     */
    public function getAsyncLanguageCode()
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_LANGUAGE_CODE);
    }

    /**
     * @return string
     */
    public function getZedResponseUrl()
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_PAYMENT_RESPONSE_URL);
    }

    /**
     * @return string
     */
    public function getYvesCheckoutPaymentFailedUrl()
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FAILED_URL);
    }

    /**
     * @return string
     */
    public function getYvesUrlForAsyncIframeResponse()
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_ASYNC_RESPONSE_URL);
    }

    /**
     * @return string
     */
    public function getIdealAuthorizeUrl()
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_IDEAL_AUTHORIZE_URL);
    }

    /**
     * @return string
     */
    public function getCreditCardPaymentFrameOrigin()
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_URL);
    }

    /**
     * @return string
     */
    public function getCreditCardPaymentFrameCustomCssUrl()
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_CUSTOM_CSS_URL);
    }

    /**
     * @return string
     */
    public function getCreditCardPaymentFramePreventAsyncRedirect()
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_PREVENT_ASYNC_REDIRECT);
    }

    /**
     * @return string
     */
    public function getEncryptionKey()
    {
        return $this->get(HeidelpayConstants::CONFIG_ENCRYPTION_KEY);
    }

    /**
     * @param string $paymentType
     *
     * @return string
     */
    public function getMerchantTransactionChannelByPaymentType($paymentType)
    {
        if ($paymentType === SharedHeidelpayConfig::PAYMENT_METHOD_SOFORT) {
            return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_SOFORT);
        }

        if ($paymentType === SharedHeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE) {
            return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_CC_3D_SECURE);
        }

        if ($paymentType === SharedHeidelpayConfig::PAYMENT_METHOD_IDEAL) {
            return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_IDEAL);
        }

        if (in_array($paymentType, [SharedHeidelpayConfig::PAYMENT_METHOD_PAYPAL_DEBIT, SharedHeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE])) {
            return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_PAYPAL);
        }

        if ($paymentType === SharedHeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT) {
            return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_EASY_CREDIT);
        }

        return static::MERCHANT_TRANSACTION_CONFIG_NOT_FOUND;
    }

    /**
     * @return string
     */
    public function getSplitPaymentEnable()
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_SPLIT_PAYMENT_ENABLE, false);
    }
}
