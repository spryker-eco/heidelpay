<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Heidelpay;

interface HeidelpayConfig
{
    const PROVIDER_NAME = 'heidelpay';

    const PAYMENT_METHOD_SOFORT = self::PROVIDER_NAME . 'Sofort';
    const PAYMENT_METHOD_CREDIT_CARD_SECURE = self::PROVIDER_NAME . 'CreditCardSecure';
    const PAYMENT_METHOD_PAYPAL_AUTHORIZE = self::PROVIDER_NAME . 'PaypalAuthorize';
    const PAYMENT_METHOD_PAYPAL_DEBIT = self::PROVIDER_NAME . 'PaypalDebit';
    const PAYMENT_METHOD_IDEAL = self::PROVIDER_NAME . 'Ideal';
    const PAYMENT_METHOD_EASY_CREDIT = self::PROVIDER_NAME . 'EasyCredit';

    const PAYMENT_OPTION_NEW_REGISTRATION = 'new-registration';
    const PAYMENT_OPTION_EXISTING_REGISTRATION = 'existing-registration';

    const TRANSACTION_TYPE_EXTERNAL_RESPONSE = 'external_response';
    const TRANSACTION_TYPE_DEBIT = 'debit';
    const TRANSACTION_TYPE_AUTHORIZE = 'authorize';
    const TRANSACTION_TYPE_AUTHORIZE_ON_REGISTRATION = 'authorize_on_registration';
    const TRANSACTION_TYPE_INITIALIZE = 'initialize';
    const TRANSACTION_TYPE_CAPTURE = 'capture';

    const EXTERNAL_RESPONSE_TRANSACTION_STATUS_OK = 'ACK';
    const CAPTURE_TRANSACTION_STATUS_OK = 'ACK';
}
