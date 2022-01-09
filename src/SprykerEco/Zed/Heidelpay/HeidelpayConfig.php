<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig as SharedHeidelpayConfig;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;

class HeidelpayConfig extends AbstractBundleConfig implements HeidelpayConfigInterface
{
    /**
     * @var string
     */
    public const MERCHANT_TRANSACTION_CONFIG_NOT_FOUND = '';

    /**
     * @var array
     */
    protected const MERCHANT_TRANSACTION_CHANNEL_PAYMENT_TYPE_MAPPING = [
        SharedHeidelpayConfig::PAYMENT_METHOD_SOFORT => HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_SOFORT,
        SharedHeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_CC_3D_SECURE,
        SharedHeidelpayConfig::PAYMENT_METHOD_IDEAL => HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_IDEAL,
        SharedHeidelpayConfig::PAYMENT_METHOD_PAYPAL_DEBIT => HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_PAYPAL,
        SharedHeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE => HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_PAYPAL,
        SharedHeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_EASY_CREDIT,
        SharedHeidelpayConfig::PAYMENT_METHOD_INVOICE_SECURED_B2C => HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_INVOICE_SECURED_B2C,
        SharedHeidelpayConfig::PAYMENT_METHOD_DIRECT_DEBIT => HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_DIRECT_DEBIT,
    ];

    /**
     * @api
     *
     * @return string
     */
    public function getMerchantSecuritySender(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_SECURITY_SENDER);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getMerchantUserLogin(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_USER_LOGIN);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getMerchantUserPassword(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_USER_PASSWORD);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getMerchantSandboxMode(): bool
    {
        return (bool)$this->get(HeidelpayConstants::CONFIG_HEIDELPAY_SANDBOX_REQUEST);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getApplicationSecret(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_APPLICATION_SECRET);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getAsyncLanguageCode(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_LANGUAGE_CODE);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getZedResponseUrl(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_PAYMENT_RESPONSE_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getEasyCreditPaymentResponseUrl(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_EASYCREDIT_PAYMENT_RESPONSE_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getYvesCheckoutPaymentFailedUrl(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FAILED_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getYvesUrlForAsyncIframeResponse(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_ASYNC_RESPONSE_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getYvesUrlForAsyncDirectDebitResponse(): string
    {
        return $this->get(HeidelpayConstants::DIRECT_DEBIT_REGISTRATION_ASYNC_RESPONSE_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getIdealAuthorizeUrl(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_IDEAL_AUTHORIZE_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCreditCardPaymentFrameOrigin(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCreditCardPaymentFrameCustomCssUrl(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_CUSTOM_CSS_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCreditCardPaymentFramePreventAsyncRedirect(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_PREVENT_ASYNC_REDIRECT);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getEncryptionKey(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_ENCRYPTION_KEY);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getEasycreditCriteriaRejectedDeliveryAddress(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_EASYCREDIT_CRITERIA_REJECTED_DELIVERY_ADDRESS);
    }

    /**
     * @api
     *
     * @return float
     */
    public function getEasycreditCriteriaGrandTotalMoreThan(): float
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_EASYCREDIT_CRITERIA_GRAND_TOTAL_MORE_THAN);
    }

    /**
     * @api
     *
     * @return float
     */
    public function getEasycreditCriteriaGrandTotalLessThan(): float
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_EASYCREDIT_CRITERIA_GRAND_TOTAL_LESS_THAN);
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getEasyCreditAllowedCountries(): array
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_EASYCREDIT_CRITERIA_COUNTRY_ISO_CODES, ['DE']);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getIsSplitPaymentEnabledKey(): bool
    {
        return $this->get(HeidelpayConstants::CONFIG_IS_SPLIT_PAYMENT_ENABLED_KEY, false);
    }

    /**
     * @api
     *
     * @param string $paymentType
     *
     * @return string
     */
    public function getMerchantTransactionChannelByPaymentType($paymentType): string
    {
        if (array_key_exists($paymentType, static::MERCHANT_TRANSACTION_CHANNEL_PAYMENT_TYPE_MAPPING)) {
            return $this->get(static::MERCHANT_TRANSACTION_CHANNEL_PAYMENT_TYPE_MAPPING[$paymentType]);
        }

        return static::MERCHANT_TRANSACTION_CONFIG_NOT_FOUND;
    }
}
