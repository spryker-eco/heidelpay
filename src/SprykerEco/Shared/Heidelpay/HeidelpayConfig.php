<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Shared\Heidelpay;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class HeidelpayConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const PROVIDER_NAME = 'heidelpay';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_SOFORT = self::PROVIDER_NAME . 'Sofort';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_CREDIT_CARD_SECURE = self::PROVIDER_NAME . 'CreditCardSecure';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_PAYPAL_AUTHORIZE = self::PROVIDER_NAME . 'PaypalAuthorize';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_PAYPAL_DEBIT = self::PROVIDER_NAME . 'PaypalDebit';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_IDEAL = self::PROVIDER_NAME . 'Ideal';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_EASY_CREDIT = self::PROVIDER_NAME . 'EasyCredit';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_INVOICE_SECURED_B2C = self::PROVIDER_NAME . 'InvoiceSecuredB2c';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_DIRECT_DEBIT = self::PROVIDER_NAME . 'DirectDebit';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_OPTION_NEW_REGISTRATION = 'new-registration';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_OPTION_EXISTING_REGISTRATION = 'existing-registration';

    /**
     * @api
     *
     * @var string
     */
    public const DIRECT_DEBIT_PAYMENT_OPTION_NEW_REGISTRATION = 'direct-debit-new-registration';

    /**
     * @api
     *
     * @var string
     */
    public const DIRECT_DEBIT_PAYMENT_OPTION_EXISTING_REGISTRATION = 'direct-debit-existing-registration';

    /**
     * @api
     *
     * @var string
     */
    public const TRANSACTION_TYPE_EXTERNAL_RESPONSE = 'external_response';

    /**
     * @api
     *
     * @var string
     */
    public const TRANSACTION_TYPE_DEBIT = 'debit';

    /**
     * @api
     *
     * @var string
     */
    public const TRANSACTION_TYPE_DEBIT_ON_REGISTRATION = 'debit_on_registration';

    /**
     * @api
     *
     * @var string
     */
    public const TRANSACTION_TYPE_FINALIZE = 'finalize';

    /**
     * @api
     *
     * @var string
     */
    public const TRANSACTION_TYPE_RESERVATION = 'reservation';

    /**
     * @api
     *
     * @var string
     */
    public const TRANSACTION_TYPE_REFUND = 'refund';

    /**
     * @api
     *
     * @var string
     */
    public const TRANSACTION_TYPE_AUTHORIZE = 'authorize';

    /**
     * @api
     *
     * @var string
     */
    public const TRANSACTION_TYPE_AUTHORIZE_ON_REGISTRATION = 'authorize_on_registration';

    /**
     * @api
     *
     * @var string
     */
    public const TRANSACTION_TYPE_INITIALIZE = 'initialize';

    /**
     * @api
     *
     * @var string
     */
    public const TRANSACTION_TYPE_CAPTURE = 'capture';

    /**
     * @api
     *
     * @var string
     */
    public const EXTERNAL_RESPONSE_TRANSACTION_STATUS_OK = 'ACK';

    /**
     * @api
     *
     * @var string
     */
    public const CAPTURE_TRANSACTION_STATUS_OK = 'ACK';

    /**
     * Specification:
     * - The response of transaction failed status.
     *
     * @api
     *
     * @var string
     */
    public const CAPTURE_TRANSACTION_STATUS_FAILED = 'NOK';

    /**
     * @api
     *
     * @var string
     */
    public const REFUND_TRANSACTION_STATUS_OK = 'ACK';

    /**
     * @api
     *
     * @var string
     */
    public const RESERVATION_TRANSACTION_STATUS_OK = 'ACK';

    /**
     * @api
     *
     * @var string
     */
    public const FINALIZE_TRANSACTION_STATUS_OK = 'ACK';

    /**
     * @api
     *
     * @var string
     */
    public const NOTIFICATION_STATUS_OK = 'ACK';

    /**
     * @api
     *
     * @var string
     */
    public const NOTIFICATION_STATUS_FAILED = 'NOK';
}
