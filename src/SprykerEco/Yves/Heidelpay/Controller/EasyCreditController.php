<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Controller;

use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function easyCreditPaymentAction(Request $request)
    {
        if (!$request->isMethod(Request::METHOD_POST)) {
//            return new RedirectResponse($this->getSummaryRedirectUrl());
        }

        $quoteTransfer = $this->getClient()->getQuoteFromSession();
        $requestAsArray = $this->getUrldecodedRequestBody($request);
        $requestAsArray = json_decode('{
  "NAME.FAMILY": "Ratenkauf",
  "CRITERION.SDK_NAME": "Heidelpay%5CPhpPaymentApi",
  "IDENTIFICATION.TRANSACTIONID": "61d5d6a65098445e05d1777ed135cc7076d760fe",
  "CRITERION.EASYCREDIT_TOTALORDERAMOUNT": "1365.10",
  "CRITERION.EASYCREDIT_MONTHLYRATECOUNT": "12",
  "ADDRESS.COUNTRY": "DE",
  "ADDRESS.STREET": "Beuthener+25",
  "FRONTEND.ENABLED": "TRUE",
  "PRESENTATION.AMOUNT": "1365.1",
  "CRITERION.EASYCREDIT_UUID": "2e2ea279-1aa5-4fba-8ea6-2503bd3d605d",
  "TRANSACTION.MODE": "CONNECTOR_TEST",
  "CONTACT.IP": "91.232.178.21",
  "CRITERION.EASYCREDIT_PRECONTRACTINFORMATIONURL": "https%3A%2F%2Fratenkauf.easycredit.de%2Fpayment%2F%23%2Fc291752f.0212140136Hb6g4rnXxDsKIWF6nzl0SqXg%2Fvorvertraglicheinfo",
  "CRITERION.SDK_VERSION": "v1.6.2",
  "CRITERION.EASYCREDIT_LASTRATEAMOUNT": "108.76",
  "PROCESSING.TIMESTAMP": "2019-02-12+13%3A01%3A36",
  "CONTACT.EMAIL": "spencor.hopkin%40spryker.com",
  "FRONTEND.RESPONSE_URL": "https%3A%2F%2Frequestloggerbin.herokuapp.com%2Fbin%2F34f4daed-dba9-42d3-bda1-c7d9d392aac4",
  "ACCOUNT.BRAND": "EASYCREDIT",
  "PROCESSING.STATUS.CODE": "90",
  "NAME.GIVEN": "Ralf",
  "CRITERION.EASYCREDIT_FIRSTRATEDUEDATE": "1554069600000",
  "IDENTIFICATION.SHORTID": "4139.0289.6048",
  "ADDRESS.CITY": "N%C3%BCrnberg",
  "CLEARING.AMOUNT": "1365.10",
  "CRITERION.EASYCREDIT_EFFECTIVEINTEREST": "8.90",
  "PROCESSING.CODE": "HP.IN.90.00",
  "PROCESSING.STATUS": "NEW",
  "SECURITY.SENDER": "31HA07BC8142C5A171745D00AD63D182",
  "CRITERION.EASYCREDIT_NOMINALINTEREST": "8.56",
  "USER.LOGIN": "31ha07bc8142c5a171744e5aef11ffd3",
  "USER.PWD": "93167DE7",
  "PROCESSING.RETURN.CODE": "000.100.112",
  "CRITERION.PAYMENT_METHOD": "EasyCreditPaymentMethod",
  "PROCESSING.RESULT": "ACK",
  "CLEARING.CURRENCY": "EUR",
  "CRITERION.EASYCREDIT_LASTRATEDUEDATE": "1583017200000",
  "FRONTEND.MODE": "WHITELABEL",
  "IDENTIFICATION.UNIQUEID": "31HA07BC817FEDDB344183809AB0BD3A",
  "CRITERION.SECRET": "04dd4449114825aa7fbea7b24ae48f34197db449f9eab12c0561af3ef7f358b76d0f91e607582028c5b3a7c6cc6dd3cb35c3b1e9ebd2e26fcd270a952758e24a",
  "PRESENTATION.CURRENCY": "EUR",
  "PROCESSING.REASON.CODE": "00",
  "lang": "DE",
  "ADDRESS.ZIP": "90471",
  "CRITERION.EASYCREDIT_ACCRUINGINTEREST": "63.66",
  "CLEARING.DESCRIPTOR": "4139.0289.6048+1593-Standard-Test-Merchant+",
  "CRITERION.EASYCREDIT_MONTHLYRATEAMOUNT": "120.00",
  "CRITERION.EASYCREDIT_TOTALRUNTIME": "12",
  "CRITERION.EASYCREDIT_TOTALAMOUNT": "1428.76",
  "CRITERION.EASYCREDIT_AMORTISATIONTEXT": "Tilgungsplan%3A+Laufzeit+12+Monate%2C+monatliche+Rate+120%2C00+EUR+%28erste+Rate%3A+110%2C26+EUR+Tilgung%2C+9%2C74+EUR+Zinsen%29%2C+Schlussrate+108%2C76+EUR+%28108%2C01+EUR+Tilgung%2C+0%2C75+EUR+Zinsen%29.+Alternativer+Tilgungsplan%3A+Laufzeit+9+Monate%2C+monatliche+Rate+158%2C00+EUR+%28erste+Rate%3A+148%2C26+EUR+Tilgung%2C+9%2C74+EUR+Zinsen%29%2C+Schlussrate+150%2C00+EUR+%28148%2C95+EUR+Tilgung%2C+1%2C05+EUR+Zinsen%29.",
  "PROCESSING.REASON": "SUCCESSFULL",
  "PROCESSING.RETURN": "Request+successfully+processed+in+%27Merchant+in+Connector+Test+Mode%27",
  "TRANSACTION.CHANNEL": "31HA07BC810DCA126FA83FA533886979",
  "FRONTEND.LANGUAGE": "DE",
  "PAYMENT.CODE": "HP.IN",
  "CRITERION.EASYCREDIT_DEVICEIDENTTOKEN": "44ae6557-6d11-42d8-b7cf-907956618c12\r\n"
}', true);

        $processingResultTransfer = $this->processEasyCreditPaymentResponse(
            $this->getClient()->filterResponseParameters($requestAsArray)
        );

        $this->hydrateEasyCreditResponseToQuote($requestAsArray, $quoteTransfer);

        return $this->redirectResponseExternal(
            $this->getCustomerRedirectUrl($processingResultTransfer)
        );
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
