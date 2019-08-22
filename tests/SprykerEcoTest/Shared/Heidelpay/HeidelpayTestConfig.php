<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Shared\Heidelpay;

interface HeidelpayTestConfig
{
    public const CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL = 'http://url-to-redirect-customer.com';
    public const HEIDELPAY_SUCCESS_RESPONSE = 'ACK';
    public const HEIDELPAY_UNSUCCESS_RESPONSE = 'NOK';

    public const HEIDELPAY_REFERENCE = '31HA07BC814BBB667B564498D53E60F7';

    public const REGISTRATION_NUMBER = '31HA07BC814CA0300B1387AB8F16C0EC';
    public const CARD_ACCOUNT_NUMBER = '471110******0000';
    public const CARD_BRAND = 'MASTER';
    public const CARD_QUOTE_HASH = '1f7f60dcf32900f266e3a516b13358792cbee777';

    public const ACCOUNT_BANK_NAME = 'COMMERZBANK KÖLN';
    public const ACCOUNT_BIC = 'COBADEFFXXX';
    public const ACCOUNT_COUNTRY = 'DE';
    public const ACCOUNT_IBAN = 'DE89370400440532013000';
    public const ACCOUNT_NUMBER = 532013000;
    public const TRANSACTION_ID = 'eabb916460b66891beddc595e9ef3433fc597c03';
}
