<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Controller;

use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayFactory getFactory()
 * @method \SprykerEco\Client\Heidelpay\HeidelpayClientInterface getClient()
 */
class EasyCreditController extends BaseHeidelpayController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function easyCreditPaymentAction(Request $request)
    {
        if (!$request->isMethod(Request::METHOD_POST)) {
            return new RedirectResponse($this->getSummaryRedirectUrl());
        }

        $quoteTransfer = $this->getClient()->getQuoteFromSession();
        $requestAsArray = $this->getUrldecodedRequestBody($request);
        $requestAsArray = json_decode('{
  "NAME.FAMILY": "Ratenkauf",
  "CRITERION.SDK_NAME": "Heidelpay%5CPhpPaymentApi",
  "IDENTIFICATION.TRANSACTIONID": "1aeef17bf21ef2ea42a47901e8ee257e256ba455",
  "CRITERION.EASYCREDIT_TOTALORDERAMOUNT": "621.00",
  "CRITERION.EASYCREDIT_MONTHLYRATECOUNT": "18",
  "ADDRESS.COUNTRY": "DE",
  "ADDRESS.STREET": "Beuthener+25",
  "FRONTEND.ENABLED": "TRUE",
  "PRESENTATION.AMOUNT": "621",
  "CRITERION.EASYCREDIT_UUID": "200c5694-43cc-45f6-8a68-562fd8a4fb8d",
  "TRANSACTION.MODE": "CONNECTOR_TEST",
  "RISKINFORMATION.CUSTOMERGUESTCHECKOUT": "0",
  "CONTACT.IP": "91.232.178.21",
  "CRITERION.EASYCREDIT_PRECONTRACTINFORMATIONURL": "https%3A%2F%2Fratenkauf.easycredit.de%2Fpayment%2F%23%2Fb956f157.0206165847tT1RmeUY9JyQVkTXf6Yi5S1A%2Fvorvertraglicheinfo",
  "CRITERION.SDK_VERSION": "v1.6.2",
  "RISKINFORMATION.CUSTOMERSINCE": "2017-10-10",
  "CRITERION.EASYCREDIT_LASTRATEAMOUNT": "36.29",
  "PROCESSING.TIMESTAMP": "2019-02-06+15%3A58%3A46",
  "CONTACT.EMAIL": "spencor.hopkin%40spryker.com",
  "FRONTEND.RESPONSE_URL": "https%3A%2F%2Frequestloggerbin.herokuapp.com%2Fbin%2F34f4daed-dba9-42d3-bda1-c7d9d392aac4",
  "ACCOUNT.BRAND": "EASYCREDIT",
  "PROCESSING.STATUS.CODE": "90",
  "NAME.GIVEN": "Ralf",
  "CRITERION.EASYCREDIT_FIRSTRATEDUEDATE": "1554069600000",
  "IDENTIFICATION.SHORTID": "4133.9512.6374",
  "ADDRESS.CITY": "N%C3%BCrnberg",
  "CLEARING.AMOUNT": "621.00",
  "CRITERION.EASYCREDIT_EFFECTIVEINTEREST": "9.20",
  "PROCESSING.CODE": "HP.IN.90.00",
  "PROCESSING.STATUS": "NEW",
  "SECURITY.SENDER": "31HA07BC8142C5A171745D00AD63D182",
  "CRITERION.EASYCREDIT_NOMINALINTEREST": "8.84",
  "USER.LOGIN": "31ha07bc8142c5a171744e5aef11ffd3",
  "USER.PWD": "93167DE7",
  "PROCESSING.RETURN.CODE": "000.100.112",
  "CRITERION.PAYMENT_METHOD": "EasyCreditPaymentMethod",
  "PROCESSING.RESULT": "ACK",
  "CLEARING.CURRENCY": "EUR",
  "CRITERION.EASYCREDIT_LASTRATEDUEDATE": "1598911200000",
  "FRONTEND.MODE": "WHITELABEL",
  "IDENTIFICATION.UNIQUEID": "31HA07BC81433F1F38FD1459C904C0EC",
  "CRITERION.SECRET": "98c00bd5b5888d3d75d7f308c70c16e98d644f90216a3c2054d4a7a701d55ad301dee1d556256113de54ffa05c5290de25bc11148a116b0a296102776e820202",
  "PRESENTATION.CURRENCY": "EUR",
  "PROCESSING.REASON.CODE": "00",
  "lang": "DE",
  "ADDRESS.ZIP": "90471",
  "CRITERION.EASYCREDIT_ACCRUINGINTEREST": "44.29",
  "CLEARING.DESCRIPTOR": "4133.9512.6374+1593-Standard-Test-Merchant+",
  "CRITERION.EASYCREDIT_MONTHLYRATEAMOUNT": "37.00",
  "CRITERION.EASYCREDIT_TOTALRUNTIME": "18",
  "CRITERION.EASYCREDIT_TOTALAMOUNT": "665.29",
  "CRITERION.EASYCREDIT_AMORTISATIONTEXT": "Tilgungsplan%3A+Laufzeit+18+Monate%2C+monatliche+Rate+37%2C00+EUR+%28erste+Rate%3A+32%2C43+EUR+Tilgung%2C+4%2C57+EUR+Zinsen%29%2C+Schlussrate+36%2C29+EUR+%2836%2C06+EUR+Tilgung%2C+0%2C23+EUR+Zinsen%29.+Alternativer+Tilgungsplan%3A+Laufzeit+15+Monate%2C+monatliche+Rate+44%2C00+EUR+%28erste+Rate%3A+39%2C43+EUR+Tilgung%2C+4%2C57+EUR+Zinsen%29%2C+Schlussrate+42%2C10+EUR+%2841%2C79+EUR+Tilgung%2C+0%2C31+EUR+Zinsen%29.",
  "PROCESSING.REASON": "SUCCESSFULL",
  "PROCESSING.RETURN": "Request+successfully+processed+in+%27Merchant+in+Connector+Test+Mode%27",
  "TRANSACTION.CHANNEL": "31HA07BC810DCA126FA83FA533886979",
  "FRONTEND.LANGUAGE": "DE",
  "PAYMENT.CODE": "HP.IN",
  "RISKINFORMATION.CUSTOMERORDERCOUNT": "5",
  "CRITERION.EASYCREDIT_DEVICEIDENTTOKEN": "db4f118a-555e-4921-b584-8916da3e62f2\r\n"
}', true);

        $processingResultTransfer = $this->processEasyCreditPaymentResponse(
            $this->getClient()->filterResponseParameters($requestAsArray)
        );

        $this->hydrateEasyCreditResponseToQuote($requestAsArray, $quoteTransfer);
        $redirectUrl = $this->getCustomerRedirectUrl($processingResultTransfer);

        return new RedirectResponse($redirectUrl);
    }

    /**
     * @param string $redirectUrl
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function streamRedirectResponse($redirectUrl)
    {
        $callback = function () use ($redirectUrl) {
            echo $redirectUrl;
        };

        return $this->streamedResponse($callback)->send();
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer $processingResultTransfer
     *
     * @return string
     */
    protected function getCustomerRedirectUrl(HeidelpayPaymentProcessingResponseTransfer $processingResultTransfer)
    {
        return $processingResultTransfer->getIsError()
            ? $this->getFailureRedirectUrl($processingResultTransfer)
            : $this->getSummaryRedirectUrl();
    }

    /**
     * @return string
     */
    protected function getSummaryRedirectUrl(): string
    {
        return $this->getConfig()->getYvesCheckoutSummaryStepUrl();
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer $processingResultTransfer
     *
     * @return string
     */
    protected function getFailureRedirectUrl(HeidelpayPaymentProcessingResponseTransfer $processingResultTransfer): string
    {
        return sprintf(
            $this->getConfig()->getYvesCheckoutPaymentFailedUrl(),
            $processingResultTransfer->getError()->getCode()
        );
    }

    /**
     * @param array $requestAsArray
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function hydrateEasyCreditResponseToQuote(array $requestAsArray, QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()
            ->createEasyCreditResponseToQuoteHydrator()
            ->hydrateEasyCreditResponseToQuote($requestAsArray, $quoteTransfer);
    }

    /**
     * @param array $requestArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    protected function processEasyCreditPaymentResponse(array $requestArray)
    {
        return $this
            ->getClient()
            ->processExternalEasyCreditPaymentResponse($requestArray);
    }
}
