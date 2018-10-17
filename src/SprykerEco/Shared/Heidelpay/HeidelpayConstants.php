<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Heidelpay;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface HeidelpayConstants
{
    public const CONFIG_HEIDELPAY_SECURITY_SENDER = 'HEIDELPAY:CONFIG_HEIDELPAY_SECURITY_SENDER';
    public const CONFIG_HEIDELPAY_USER_LOGIN = 'HEIDELPAY:CONFIG_HEIDELPAY_USER_LOGIN';
    public const CONFIG_HEIDELPAY_USER_PASSWORD = 'HEIDELPAY:CONFIG_HEIDELPAY_USER_PASSWORD';
    public const CONFIG_HEIDELPAY_TRANSACTION_CHANNEL = 'HEIDELPAY:CONFIG_HEIDELPAY_TRANSACTION_CHANNEL';
    public const CONFIG_HEIDELPAY_SANDBOX_REQUEST = 'HEIDELPAY:CONFIG_HEIDELPAY_SANDBOX_REQUEST';
    public const CONFIG_HEIDELPAY_APPLICATION_SECRET = 'HEIDELPAY:CONFIG_HEIDELPAY_APPLICATION_SECRET';

    public const CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_CC_3D_SECURE = 'HEIDELPAY:CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_CC_3D_SECURE';
    public const CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_PAYPAL = 'HEIDELPAY:CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_PAYPAL';
    public const CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_IDEAL = 'HEIDELPAY:CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_IDEAL';
    public const CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_SOFORT = 'HEIDELPAY:CONFIG_HEIDELPAY_TRANSACTION_CHANNEL_SOFORT';

    public const CONFIG_HEIDELPAY_LANGUAGE_CODE = 'HEIDELPAY:CONFIG_HEIDELPAY_LANGUAGE_CODE';
    public const CONFIG_HEIDELPAY_PAYMENT_RESPONSE_URL = 'HEIDELPAY:CONFIG_HEIDELPAY_PAYMENT_RESPONSE_URL';

    public const CONFIG_YVES_URL = 'HEIDELPAY:CONFIG_YVES_URL';

    public const CONFIG_YVES_CHECKOUT_SUCCESS_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_SUCCESS_URL';
    public const CONFIG_YVES_CHECKOUT_PAYMENT_FAILED_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_PAYMENT_FAILED_URL';
    public const CONFIG_YVES_CHECKOUT_SUMMARY_STEP_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_SUMMARY_STEP_URL';
    public const CONFIG_YVES_CHECKOUT_PAYMENT_STEP_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_PAYMENT_STEP_URL';
    public const CONFIG_YVES_CHECKOUT_REGISTRATION_SUCCESS_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_REGISTRATION_SUCCESS_URL';
    public const CONFIG_YVES_CHECKOUT_PAYMENT_STEP_PATH = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_PAYMENT_STEP_PATH';
    public const CONFIG_YVES_CHECKOUT_ASYNC_RESPONSE_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_ASYNC_RESPONSE_URL';
    public const CONFIG_YVES_CHECKOUT_IDEAL_AUTHORIZE_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_IDEAL_AUTHORIZE_URL';
    public const CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_CUSTOM_CSS_URL = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_CUSTOM_CSS_URL';
    public const CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_PREVENT_ASYNC_REDIRECT = 'HEIDELPAY:CONFIG_YVES_CHECKOUT_PAYMENT_FRAME_PREVENT_ASYNC_REDIRECT';

    public const CONFIG_ENCRYPTION_KEY = 'HEIDELPAY:CONFIG_ENCRYPTION_KEY';
}
