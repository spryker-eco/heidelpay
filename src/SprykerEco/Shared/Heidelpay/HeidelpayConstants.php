<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Shared\Heidelpay;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface HeidelpayConstants
{
    /**
     * Specification:
     * - Reject criteria for EasyCredit by delivery address.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_EASYCREDIT_CRITERIA_REJECTED_DELIVERY_ADDRESS = 'HEIDELPAY:CONFIG_HEIDELPAY_EASYCREDIT_CRITERIA_REJECTED_DELIVERY_ADDRESS';

    /**
     * Specification:
     * - Reject criteria for EasyCredit by minimum total amount.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_EASYCREDIT_CRITERIA_GRAND_TOTAL_LESS_THAN = 'HEIDELPAY:CONFIG_HEIDELPAY_EASYCREDIT_CRITERIA_GRAND_TOTAL_LESS_THAN';

    /**
     * Specification:
     * - Reject criteria for EasyCredit by maximum total amount.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_EASYCREDIT_CRITERIA_GRAND_TOTAL_MORE_THAN = 'HEIDELPAY:CONFIG_HEIDELPAY_EASYCREDIT_CRITERIA_GRAND_TOTAL_MORE_THAN';

    /**
     * Specification:
     * - Security sender configuration.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_SECURITY_SENDER = 'HEIDELPAY:CONFIG_HEIDELPAY_SECURITY_SENDER';

    /**
     * Specification:
     * - User login configuration.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_USER_LOGIN = 'HEIDELPAY:CONFIG_HEIDELPAY_USER_LOGIN';

    /**
     * Specification:
     * - User password configuration.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_USER_PASSWORD = 'HEIDELPAY:CONFIG_HEIDELPAY_USER_PASSWORD';

    /**
     * Specification:
     * - Transaction channel configuration.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_TRANSACTION_CHANNEL = 'HEIDELPAY:CONFIG_HEIDELPAY_TRANSACTION_CHANNEL';

    /**
     * Specification:
     * - Configuration represents if sandbox is enabled.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_SANDBOX_REQUEST = 'HEIDELPAY:CONFIG_HEIDELPAY_SANDBOX_REQUEST';

    /**
     * Specification:
     * - Application secret configuration.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_APPLICATION_SECRET = 'HEIDELPAY:CONFIG_HEIDELPAY_APPLICATION_SECRET';

    /**
     * Specification:
     * - Transaction channel value for CreditCard 3D Secure payment method.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_CC_3D_SECURE = 'HEIDELPAY:CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_CC_3D_SECURE';

    /**
     * Specification:
     * - Transaction channel value for PayPal payment method.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_PAYPAL = 'HEIDELPAY:CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_PAYPAL';

    /**
     * Specification:
     * - Transaction channel value for Ideal payment method.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_IDEAL = 'HEIDELPAY:CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_IDEAL';

    /**
     * Specification:
     * - Transaction channel value for Sofort payment method.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_SOFORT = 'HEIDELPAY:CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_SOFORT';

    /**
     * Specification:
     * - Transaction channel value for EasyCredit payment method.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_EASY_CREDIT = 'HEIDELPAY:CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_EASY_CREDIT';

    /**
     * Specification:
     * - Transaction channel value for InvoiceSecuredB2c payment method.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_INVOICE_SECURED_B2C = 'HEIDELPAY:CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_INVOICE_SECURED_B2C';

    /**
     * Specification:
     * - Language code, store specific.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_LANGUAGE_CODE = 'HEIDELPAY:CONFIG_HEIDELPAY_LANGUAGE_CODE';

    /**
     * Specification:
     * - Url for receiving responses from Heidelpay.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_PAYMENT_RESPONSE_URL = 'HEIDELPAY:CONFIG_HEIDELPAY_PAYMENT_RESPONSE_URL';

    /**
     * Specification:
     * - Url for receiving requests from Heidelpay for EasyCredit payment method.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_EASYCREDIT_PAYMENT_URL = 'HEIDELPAY:CONFIG_HEIDELPAY_EASYCREDIT_PAYMENT_URL';

    /**
     * Specification:
     * - Url for receiving response from Heidelpay for EasyCredit payment method.
     *
     * @api
     */
    public const CONFIG_HEIDELPAY_EASYCREDIT_PAYMENT_RESPONSE_URL = 'HEIDELPAY:CONFIG_HEIDELPAY_EASYCREDIT_PAYMENT_RESPONSE_URL';

    /**
     * Specification:
     * - Url for receiving responses asynchronously from Heidelpay for EasyCredit initialize api call.
     *
     * @api
     */
    public const CONFIG_YVES_CHECKOUT_EASYCREDIT_INITIALIZE_PAYMENT_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_EASYCREDIT_INITIALIZE_PAYMENT_URL';

    /**
     * Specification:
     * - Spryker Yves url.
     *
     * @api
     */
    public const CONFIG_YVES_URL = 'HEIDELPAY:CONFIG_YVES_URL';

    /**
     * Specification:
     * - Spryker checkout success url.
     *
     * @api
     */
    public const CONFIG_YVES_CHECKOUT_SUCCESS_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_SUCCESS_URL';

    /**
     * Specification:
     * - Spryker payment failed url.
     *
     * @api
     */
    public const CONFIG_YVES_CHECKOUT_PAYMENT_FAILED_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_PAYMENT_FAILED_URL';

    /**
     * Specification:
     * - Spryker checkout summary step url.
     *
     * @api
     */
    public const CONFIG_YVES_CHECKOUT_SUMMARY_STEP_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_SUMMARY_STEP_URL';

    /**
     * Specification:
     * - Spryker checkout payment step url.
     *
     * @api
     */
    public const CONFIG_YVES_CHECKOUT_PAYMENT_STEP_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_PAYMENT_STEP_URL';

    /**
     * Specification:
     * - Spryker checkout registration success url.
     *
     * @api
     */
    public const CONFIG_YVES_CHECKOUT_REGISTRATION_SUCCESS_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_REGISTRATION_SUCCESS_URL';

    /**
     * Specification:
     * - Spryker checkout payment step path.
     *
     * @api
     */
    public const CONFIG_YVES_CHECKOUT_PAYMENT_STEP_PATH = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_PAYMENT_STEP_PATH';

    /**
     * Specification:
     * - Url for receiving responses asynchronously for CreditCard payment method.
     *
     * @api
     */
    public const CONFIG_YVES_CHECKOUT_ASYNC_RESPONSE_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_ASYNC_RESPONSE_URL';

    /**
     * Specification:
     * - Ideal payment method authorize url.
     *
     * @api
     */
    public const CONFIG_YVES_CHECKOUT_IDEAL_AUTHORIZE_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_IDEAL_AUTHORIZE_URL';

    /**
     * Specification:
     * - Iframe custom styles url.
     *
     * @api
     */
    public const CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_CUSTOM_CSS_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_CUSTOM_CSS_URL';

    /**
     * Specification:
     * - Represents setting for preventing async redirect for payment iframe.
     *
     * @api
     */
    public const CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_PREVENT_ASYNC_REDIRECT = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_PREVENT_ASYNC_REDIRECT';

    /**
     * Specification:
     * - Encryption key.
     *
     * @api
     */
    public const CONFIG_ENCRYPTION_KEY = 'HEIDELPAY:CONFIG_ENCRYPTION_KEY';

    /**
     * Specification:
     * - Enables split delivery.
     *
     * @api
     */
    public const CONFIG_IS_SPLIT_PAYMENT_ENABLED_KEY = 'HEIDELPAY:CONFIG_IS_SPLIT_PAYMENT_ENABLED_KEY';

    /**
     * Specification:
     * - EasyCredit logo url.
     *
     * @api
     */
    public const CONFIG_EASY_CREDIT_LOGO_URL = 'HEIDELPAY:CONFIG_EASY_CREDIT_LOGO_URL';

    /**
     * Specification:
     * - EasyCredit info link.
     *
     * @api
     */
    public const CONFIG_EASY_CREDIT_INFO_LINK = 'HEIDELPAY:CONFIG_EASY_CREDIT_INFO_LINK';
}
