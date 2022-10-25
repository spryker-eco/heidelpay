<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

interface HeidelpayTestConfig
{
    /**
     * @var string
     */
    public const CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL = 'http://url-to-redirect-customer.com';

    /**
     * @var string
     */
    public const HEIDELPAY_SUCCESS_RESPONSE = 'ACK';

    /**
     * @var string
     */
    public const HEIDELPAY_UNSUCCESS_RESPONSE = 'NOK';

    /**
     * @var string
     */
    public const HEIDELPAY_REFERENCE = '31HA07BC814BBB667B564498D53E60F7';

    /**
     * @var string
     */
    public const REGISTRATION_NUMBER = '31HA07BC814CA0300B1387AB8F16C0EC';

    /**
     * @var string
     */
    public const CARD_ACCOUNT_NUMBER = '471110******0000';

    /**
     * @var string
     */
    public const CARD_BRAND = 'MASTER';

    /**
     * @var string
     */
    public const CARD_QUOTE_HASH = '1f7f60dcf32900f266e3a516b13358792cbee777';

    /**
     * @var string
     */
    public const ACCOUNT_BANK_NAME = 'COMMERZBANK KÖLN';

    /**
     * @var string
     */
    public const ACCOUNT_BIC = 'COBADEFFXXX';

    /**
     * @var string
     */
    public const ACCOUNT_COUNTRY = 'DE';

    /**
     * @var string
     */
    public const ACCOUNT_IBAN = 'DE89370400440532013000';

    /**
     * @var int
     */
    public const ACCOUNT_NUMBER = 532013000;

    /**
     * @var string
     */
    public const TRANSACTION_ID = 'eabb916460b66891beddc595e9ef3433fc597c03';
}
