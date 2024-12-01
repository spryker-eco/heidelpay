<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Shared\Sales\SalesConstants;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;

class HeidelpayConfigurationBuilder
{
    /**
     * @return array
     */
    public function getHeidelpayConfigurationOptions(): array
    {
        $config = [];
        $config[HeidelpayConstants::CONFIG_ENCRYPTION_KEY] = 'encryption_key';
        $config[ApplicationConstants::HOST_YVES] = 'www.de.spryker.test';
        $YVES_HOST_PROTOCOL = 'http';
        $config[ApplicationConstants::BASE_URL_YVES] = sprintf(
            '%s://%s',
            $YVES_HOST_PROTOCOL,
            $config[ApplicationConstants::HOST_YVES],
        );
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_PAYMENT_RESPONSE_URL] = $YVES_HOST_PROTOCOL . '://' . $config[ApplicationConstants::HOST_YVES] . '/heidelpay/payment';
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_EASYCREDIT_CRITERIA_REJECTED_DELIVERY_ADDRESS] = 'Packstation';
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_EASYCREDIT_CRITERIA_GRAND_TOTAL_LESS_THAN] = 200;
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_EASYCREDIT_CRITERIA_GRAND_TOTAL_MORE_THAN] = 5000;

        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_SUCCESS_URL] = $config[ApplicationConstants::HOST_YVES] . '/checkout/success';
        $config[HeidelpayConstants::CONFIG_YVES_URL] = $config[ApplicationConstants::HOST_YVES];
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FAILED_URL] = $config[ApplicationConstants::HOST_YVES] . '/heidelpay/payment-failed?error_code=%s';
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_IDEAL_AUTHORIZE_URL] = $config[ApplicationConstants::HOST_YVES] . '/heidelpay/ideal-authorize';
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_STEP_PATH] = '/checkout/payment';
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_SUMMARY_STEP_URL] = $config[ApplicationConstants::HOST_YVES] . '/checkout/summary';
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_ASYNC_RESPONSE_URL] = $config[ApplicationConstants::HOST_YVES] . '/heidelpay/cc-register-response';
        $config[HeidelpayConstants::DIRECT_DEBIT_REGISTRATION_ASYNC_RESPONSE_URL] = $config[ApplicationConstants::HOST_YVES] . '/heidelpay/dd-register-response';
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_REGISTRATION_SUCCESS_URL] = $config[ApplicationConstants::HOST_YVES] . '/heidelpay/cc-register-success?id_registration=%s';

        // Merchant config values, got from Heidelpay
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_SECURITY_SENDER] = '31HA07BC8142C5A171745D00AD63D182';
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_USER_LOGIN] = '31ha07bc8142c5a171744e5aef11ffd3';
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_USER_PASSWORD] = '93167DE7';
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_CC_3D_SECURE] = '31HA07BC8142C5A171749A60D979B6E4';
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_PAYPAL] = '31HA07BC8142C5A171749A60D979B6E4';
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_IDEAL] = '31HA07BC8142C5A171744B56E61281E5';
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_SOFORT] = '31HA07BC8142C5A171749CDAA43365D2';
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_EASY_CREDIT] = '31HA07BC810DCA126FA83FA533886979';
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_DIRECT_DEBIT] = '31HA07BC8142C5A171744F3D6D155865';
        // Shop configuration values
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_APPLICATION_SECRET] = 'debug_secret';
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_SANDBOX_REQUEST] = true;
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_LANGUAGE_CODE] = 'DE';
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_PAYMENT_RESPONSE_URL] = 'https://' . $config[ApplicationConstants::HOST_YVES] . '/heidelpay/payment';
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_EASYCREDIT_INITIALIZE_PAYMENT_URL] = $config[ApplicationConstants::BASE_URL_YVES] . '/heidelpay/easyCreditInitializePayment';
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_EASYCREDIT_PAYMENT_RESPONSE_URL] = $config[ApplicationConstants::BASE_URL_YVES] . '/heidelpay/easyCreditPayment';
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_SUCCESS_URL] = 'http://' . $config[ApplicationConstants::HOST_YVES] . '/checkout/success';
        $config[HeidelpayConstants::CONFIG_YVES_URL] = 'http://' . $config[ApplicationConstants::HOST_YVES];
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FAILED_URL] = 'http://' . $config[ApplicationConstants::HOST_YVES] . '/heidelpay/payment-failed?error_code=%s';
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_IDEAL_AUTHORIZE_URL] = 'http://' . $config[ApplicationConstants::HOST_YVES] . '/heidelpay/ideal-authorize';
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_STEP_PATH] = '/checkout/payment';
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_SUMMARY_STEP_URL] = 'http://' . $config[ApplicationConstants::HOST_YVES] . '/checkout/summary';
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_ASYNC_RESPONSE_URL] = 'http://' . $config[ApplicationConstants::HOST_YVES] . '/heidelpay/cc-register-response';
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_REGISTRATION_SUCCESS_URL] = 'http://' . $config[ApplicationConstants::HOST_YVES] . '/heidelpay/cc-register-success?id_registration=%s';
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_CUSTOM_CSS_URL] = '';
        $config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_PREVENT_ASYNC_REDIRECT] = 'FALSE';

        $config[KernelConstants::CORE_NAMESPACES] = ['Spryker', 'SprykerEco'];
        $config[PropelConstants::SCHEMA_FILE_PATH_PATTERN] = APPLICATION_VENDOR_DIR . '/*/*/src/*/Zed/*/Persistence/Propel/Schema/';

        $config[SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING] = [
            HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => 'HeidelpayCreditCardSecureAuthorize01',
            HeidelpayConfig::PAYMENT_METHOD_SOFORT => 'HeidelpaySofort01',
            HeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE => 'HeidelpayPaypalAuthorize01',
            HeidelpayConfig::PAYMENT_METHOD_PAYPAL_DEBIT => 'HeidelpayPaypalDebit01',
            HeidelpayConfig::PAYMENT_METHOD_IDEAL => 'HeidelpayIdeal01',
            HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => 'HeidelpayEasyCredit01',
        ];

        return $config;
    }
}
