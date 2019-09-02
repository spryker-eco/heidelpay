<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Shared\Heidelpay;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class HeidelpayConfig extends AbstractBundleConfig
{
    public const PROVIDER_NAME = 'heidelpay';

    public const PAYMENT_METHOD_SOFORT = self::PROVIDER_NAME . 'Sofort';
    public const PAYMENT_METHOD_CREDIT_CARD_SECURE = self::PROVIDER_NAME . 'CreditCardSecure';
    public const PAYMENT_METHOD_PAYPAL_AUTHORIZE = self::PROVIDER_NAME . 'PaypalAuthorize';
    public const PAYMENT_METHOD_PAYPAL_DEBIT = self::PROVIDER_NAME . 'PaypalDebit';
    public const PAYMENT_METHOD_IDEAL = self::PROVIDER_NAME . 'Ideal';
    public const PAYMENT_METHOD_EASY_CREDIT = self::PROVIDER_NAME . 'EasyCredit';
    public const PAYMENT_METHOD_INVOICE_SECURED_B2C = self::PROVIDER_NAME . 'InvoiceSecuredB2c';

    public const PAYMENT_OPTION_NEW_REGISTRATION = 'new-registration';
    public const PAYMENT_OPTION_EXISTING_REGISTRATION = 'existing-registration';

    public const TRANSACTION_TYPE_EXTERNAL_RESPONSE = 'external_response';
    public const TRANSACTION_TYPE_DEBIT = 'debit';
    public const TRANSACTION_TYPE_FINALIZE = 'finalize';
    public const TRANSACTION_TYPE_RESERVATION = 'reservation';
    public const TRANSACTION_TYPE_AUTHORIZE = 'authorize';
    public const TRANSACTION_TYPE_AUTHORIZE_ON_REGISTRATION = 'authorize_on_registration';
    public const TRANSACTION_TYPE_INITIALIZE = 'initialize';
    public const TRANSACTION_TYPE_CAPTURE = 'capture';

    public const EXTERNAL_RESPONSE_TRANSACTION_STATUS_OK = 'ACK';
    public const CAPTURE_TRANSACTION_STATUS_OK = 'ACK';
    public const RESERVATION_TRANSACTION_STATUS_OK = 'ACK';
    public const FINALIZE_TRANSACTION_STATUS_OK = 'ACK';
    public const NOTIFICATION_STATUS_OK = 'ACK';
    public const NOTIFICATION_STATUS_FAILED = 'NOK';
}
