<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\Action;

trait HeidelpayResponseTrait
{
    public function getHeidelpayResponseTemplate(array $param)
    {
        return [
        'NAME_FAMILY' => $param[self::CUSTOMER_FULL_NAME],//"Muster"
        'CRITERION_SDK_NAME' => $param[static::CRITERION_SDK_NAME],//"Heidelpay\PhpApi"
        'ACCOUNT_IDENTIFICATION' => "3750.9565.0284",
        'IDENTIFICATION_TRANSACTIONID' => $param[static::TRANSACRTION_ID],
        'ACCOUNT_BIC' => "SFRTDE20XXX",
        'IDENTIFICATION_REFERENCEID' => "31HA07BC8169E064899027EF30B26E04",
        'ADDRESS_COUNTRY' => "DE",
        'ADDRESS_STREET' => "Any st 23",
        'FRONTEND_ENABLED' => "TRUE",
        'PRESENTATION_AMOUNT' => "272.62",
        'TRANSACTION_MODE' => "CONNECTOR_TEST",
        'CONTACT_IP' => "5.145.176.51",
        'CRITERION_SDK_VERSION' => "17.9.27",
        'PROCESSING_TIMESTAMP' => "2017-11-20 09:18:27",
        'CONTACT_EMAIL' => $param[static::EMAIL],
        'FRONTEND_RESPONSE_URL' => $param[static::RESPONSE_URL],
        'REQUEST_VERSION' => "1.0",
        'ACCOUNT_BRAND' => $param[static::PAYMENT_BRAND], //"SOFORT",
        'PROCESSING_STATUS_CODE' => "90",
        'NAME_GIVEN' => $param[static::CUSTOMER_NAME],
        'IDENTIFICATION_SHORTID' => "3750.9590.7356",
        'ADDRESS_CITY' => "Berlin",
        'CLEARING_AMOUNT' => $param[static::AMOUNT],
        'ACCOUNT_HOLDER' => $param[static::FULL_NAME],//"Max Mustermann",
        'PROCESSING_CODE' => "OT.RC.90.00",
        'PROCESSING_STATUS' => "NEW",
        'SECURITY_SENDER' => $param[static::SECURITY_SENDER],//"31HA07BC8142C5A171745D00AD63D182",
        'USER_LOGIN' => $param[static::USER_LOGIN], //"31ha07bc8142c5a171744e5aef11ffd3",
        'USER_PWD' => $param[static::USER_PWD],//"93167DE7",
        'PROCESSING_RETURN_CODE' => "000.100.112",
        'CRITERION_PAYMENT_METHOD' => $param[static::PAYMENT_METHOD], //"SofortPaymentMethod",
        'PROCESSING_RESULT' => $param[static::PROCESSING_RESULT],
        'ACCOUNT_BANK' => "88888888",
        'CLEARING_CURRENCY' => "EUR",
        'FRONTEND_MODE' => "WHITELABEL",
        'IDENTIFICATION_UNIQUEID' => "31HA07BC8169E06489904BB30BD37503",
        'CRITERION_SECRET' => $param[static::CRITERION_SECRET],//"eca3a77e6fa8cd807da7996d16cf95ed26b7fea27d8bdeb579f40dc229d79133cad84dd927f31ed05e4d317310e31cd33ba07d52603116ae22edb0bc9cb5be5c"
        'PRESENTATION_CURRENCY' => "EUR",
        'NAME_COMPANY' => "somecompany",
        'PROCESSING_REASON_CODE' => "00",
        'lang' => "DE",
        'ADDRESS_ZIP' => "13353",
        'ACCOUNT_NUMBER' => "23456789",
        'CLEARING_DESCRIPTOR' => "3750.9565.0284 1593-HPC TEST-Merchant direkt ",
        'ACCOUNT_BANKNAME' => "Demo Bank",
        'PROCESSING_REASON' => "SUCCESSFULL",
        'ACCOUNT_COUNTRY' => "DE",
        'PROCESSING_RETURN' => "Request successfully processed in 'Merchant in Connector Test Mode'",
        'TRANSACTION_CHANNEL' => $param[static::TRANSACRTION_CHANNEL], //"31HA07BC8142C5A171749CDAA43365D2",
        'FRONTEND_LANGUAGE' => "DE",
        'PAYMENT_CODE' => "OT.RC",
        'ACCOUNT_IBAN' => "DE06000000000023456789",
        ];
    }

}