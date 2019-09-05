<?php
/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

/**
 * Copy over the following configs to your config
 */

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Zed\Oms\OmsConfig;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;

// Heidelpay configuration

// Merchant config values, got from Heidelpay
$config[HeidelpayConstants::CONFIG_HEIDELPAY_SECURITY_SENDER] = '';
$config[HeidelpayConstants::CONFIG_HEIDELPAY_USER_LOGIN] = '';
$config[HeidelpayConstants::CONFIG_HEIDELPAY_USER_PASSWORD] = '';

$config[HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_CC_3D_SECURE] = '';
$config[HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_PAYPAL] = '';
$config[HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_IDEAL] = '';
$config[HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_SOFORT] = '';
$config[HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_EASY_CREDIT] = '';
$config[HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_INVOICE_SECURED_B2C] = '';
$config[HeidelpayConstants::CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_DIRECT_DEBIT] = '';

// Shop configuration values
$config[HeidelpayConstants::CONFIG_HEIDELPAY_APPLICATION_SECRET] = '';
$config[HeidelpayConstants::CONFIG_HEIDELPAY_SANDBOX_REQUEST] = true;

$config[HeidelpayConstants::CONFIG_HEIDELPAY_LANGUAGE_CODE] = 'DE';
$config[HeidelpayConstants::CONFIG_HEIDELPAY_PAYMENT_RESPONSE_URL] = 'https://' . $config[ApplicationConstants::HOST_YVES] . '/heidelpay/payment';
$config[HeidelpayConstants::CONFIG_HEIDELPAY_EASYCREDIT_PAYMENT_URL] = 'https://' . $config[ApplicationConstants::HOST_YVES] . '/heidelpay/easyCreditPayment';
$config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_EASYCREDIT_INITIALIZE_PAYMENT_URL] = 'https://' . $config[ApplicationConstants::HOST_YVES] . '/heidelpay/easyCreditPaymentInitializePayment';
$config[HeidelpayConstants::CONFIG_HEIDELPAY_EASYCREDIT_PAYMENT_RESPONSE_URL] = 'https://' . $config[ApplicationConstants::HOST_YVES] . '/heidelpay/payment';
$config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_SUCCESS_URL] = 'http://' . $config[ApplicationConstants::HOST_YVES] . '/checkout/success';
$config[HeidelpayConstants::CONFIG_YVES_URL] = 'http://' . $config[ApplicationConstants::HOST_YVES];
$config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FAILED_URL] = 'http://' . $config[ApplicationConstants::HOST_YVES] . '/heidelpay/payment-failed?error_code=%s';
$config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_IDEAL_AUTHORIZE_URL] = 'http://' . $config[ApplicationConstants::HOST_YVES] . '/heidelpay/ideal-authorize';
$config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_STEP_PATH] = '/checkout/payment';
$config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_SUMMARY_STEP_URL] = 'http://' . $config[ApplicationConstants::HOST_YVES] . '/checkout/summary';
$config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_ASYNC_RESPONSE_URL] = 'http://' . $config[ApplicationConstants::HOST_YVES] . '/heidelpay/cc-register-response';
$config[HeidelpayConstants::DIRECT_DEBIT_REGISTRATION_ASYNC_RESPONSE_URL] = $config[HeidelpayConstants::CONFIG_YVES_URL] . '/heidelpay/dd-register-response';
$config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_REGISTRATION_SUCCESS_URL] = 'http://' . $config[ApplicationConstants::HOST_YVES] . '/heidelpay/cc-register-success?id_registration=%s';
$config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_CUSTOM_CSS_URL] = '';
$config[HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_PREVENT_ASYNC_REDIRECT] = "FALSE";

$config[OmsConstants::PROCESS_LOCATION] = [
    OmsConfig::DEFAULT_PROCESS_LOCATION,
    APPLICATION_ROOT_DIR . '/vendor/spryker-eco/heidelpay/config/Zed/Oms',
];

$config[OmsConstants::ACTIVE_PROCESSES] = [
    'HeidelpaySofort01',
    'HeidelpayPaypalAuthorize01',
    'HeidelpayPaypalDebit01',
    'HeidelpayIdeal01',
    'HeidelpayCreditCardSecureAuthorize01',
];

$config[SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING] = [
    HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => 'HeidelpayCreditCardSecureAuthorize01',
    HeidelpayConfig::PAYMENT_METHOD_SOFORT => 'HeidelpaySofort01',
    HeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE => 'HeidelpayPaypalAuthorize01',
    HeidelpayConfig::PAYMENT_METHOD_PAYPAL_DEBIT => 'HeidelpayPaypalDebit01',
    HeidelpayConfig::PAYMENT_METHOD_IDEAL => 'HeidelpayIdeal01',
];
