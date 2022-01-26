<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response;

trait HeidelpayEasyCreditResponseTrait
{
    /**
     * @param array $responseParam
     *
     * @return array
     */
    public function getHeidelpayResponseTemplate(array $responseParam): array
    {
        return [
            'ACCOUNT_BANK' => '88888888',
            'ACCOUNT_BANKNAME' => 'Demo Bank',
            'ACCOUNT_BIC' => 'SFRTDE20XXX',
            'ACCOUNT_BRAND' => $responseParam[static::PAYMENT_BRAND], //"SOFORT",
            'ACCOUNT_COUNTRY' => 'DE',
            'ACCOUNT_HOLDER' => $responseParam[static::FULL_NAME], //"Max Mustermann",
            'ACCOUNT_IBAN' => 'DE06000000000023456789',
            'ACCOUNT_IDENTIFICATION' => '3750.9565.0284',
            'ACCOUNT_NUMBER' => '23456789',
            'ADDRESS_CITY' => 'Berlin',
            'ADDRESS_COUNTRY' => 'DE',
            'ADDRESS_STREET' => 'Any st 23',
            'ADDRESS_ZIP' => '13353',
            'CLEARING_AMOUNT' => $responseParam[static::AMOUNT],
            'CLEARING_CURRENCY' => 'EUR',
            'CLEARING_DESCRIPTOR' => '3750.9565.0284 1593-HPC TEST-Merchant direkt ',
            'CONTACT_EMAIL' => $responseParam[static::EMAIL],
            'CONTACT_IP' => '5.145.176.51',
            'CRITERION_PAYMENT_METHOD' => $responseParam[static::PAYMENT_METHOD], //"SofortPaymentMethod",
            'CRITERION_SDK_NAME' => $responseParam[static::CRITERION_SDK_NAME], //"Heidelpay\PhpPaymentApi"
            'CRITERION_SDK_VERSION' => '17.9.27',
            'CRITERION_SECRET' => $responseParam[static::CRITERION_SECRET], //"eca3a77e6fa8cd807da7996d16cf95ed26b7fea27d8bdeb579f40dc229d79133cad84dd927f31ed05e4d317310e31cd33ba07d52603116ae22edb0bc9cb5be5c"
            'FRONTEND_ENABLED' => 'TRUE',
            'FRONTEND_LANGUAGE' => 'DE',
            'FRONTEND_MODE' => 'WHITELABEL',
            'FRONTEND_RESPONSE_URL' => $responseParam[static::RESPONSE_URL],
            'IDENTIFICATION_REFERENCEID' => '31HA07BC8169E064899027EF30B26E04',
            'IDENTIFICATION_SHORTID' => '3750.9590.7356',
            'IDENTIFICATION_TRANSACTIONID' => $responseParam[static::TRANSACRTION_ID],
            'IDENTIFICATION_UNIQUEID' => $responseParam[static::IDENTIFICATION_UNIQUEID],
            'lang' => 'DE',
            'NAME_COMPANY' => 'somecompany',
            'NAME_FAMILY' => $responseParam[static::CUSTOMER_FULL_NAME], //"Muster"
            'NAME_GIVEN' => $responseParam[static::CUSTOMER_NAME],
            'PAYMENT_CODE' => $responseParam[static::PAYMENT_CODE],
            'PRESENTATION_AMOUNT' => '272.62',
            'PRESENTATION_CURRENCY' => 'EUR',
            'PROCESSING_CODE' => sprintf(
                '%s.%s.%s',
                $responseParam[static::PAYMENT_CODE],
                $responseParam[static::PROCESSING_STATUS_CODE],
                $responseParam[static::PROCESSING_REASON_CODE],
            ),
            'PROCESSING_REASON' => 'SUCCESSFULL',
            'PROCESSING_REASON_CODE' => $responseParam[static::PROCESSING_REASON_CODE],
            'PROCESSING_RESULT' => $responseParam[static::PROCESSING_RESULT],
            'PROCESSING_RETURN' => $responseParam[static::PROCESSING_RETURN],
            'PROCESSING_RETURN_CODE' => '000.100.112',
            'PROCESSING_STATUS' => 'NEW',
            'PROCESSING_STATUS_CODE' => $responseParam[static::PROCESSING_STATUS_CODE],
            'PROCESSING_TIMESTAMP' => '2017-11-20 09:18:27',
            'REQUEST_VERSION' => '1.0',
            'SECURITY_SENDER' => $responseParam[static::SECURITY_SENDER], //"31HA07BC8142C5A171745D00AD63D182",
            'TRANSACTION_CHANNEL' => $responseParam[static::TRANSACRTION_CHANNEL], //"31HA07BC8142C5A171749CDAA43365D2",
            'TRANSACTION_MODE' => 'CONNECTOR_TEST',
            'USER_LOGIN' => $responseParam[static::USER_LOGIN], //"31ha07bc8142c5a171744e5aef11ffd3",
            'USER_PWD' => $responseParam[static::USER_PWD], //"93167DE7",
        ];
    }
}
