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
//            throw new NotFoundHttpException();
        }

        $quoteTransfer = $this->getClient()->getQuoteFromSession();
        $requestAsArray = $this->getUrldecodedRequestBody($request);
        $requestAsArray = [
            'NAME.FAMILY' => 'Ratenkauf',
            'CRITERION.SDK_NAME' => 'Heidelpay%5CPhpPaymentApi',
            'IDENTIFICATION.TRANSACTIONID' => '0a668619034f939b3413c5a11b85b776e4a774c4',
            'CRITERION.EASYCREDIT_TOTALORDERAMOUNT' => '2173.50',
            'CRITERION.EASYCREDIT_MONTHLYRATECOUNT' => '18',
            'ADDRESS.COUNTRY' => 'DE',
            'ADDRESS.STREET' => 'Beuthener+25',
            'FRONTEND.ENABLED' => 'TRUE',
            'PRESENTATION.AMOUNT' => '2173.5',
            'CRITERION.EASYCREDIT_UUID' => '4c2a484a-18b3-4050-bdd9-efddc73dda5f',
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'CONTACT.IP' => '91.232.178.21',
            'CRITERION.EASYCREDIT_PRECONTRACTINFORMATIONURL' => 'https%3A%2F%2Fratenkauf.easycredit.de%2Fpayment%2F%23%2Fb956f157.0205124616cELDJHJlbu1ZvebsehQWRK6H%2Fvorvertraglicheinfo',
            'CRITERION.SDK_VERSION' => 'v1.6.2',
            'CRITERION.EASYCREDIT_LASTRATEAMOUNT' => '117.91',
            'PROCESSING.TIMESTAMP' => '2019-02-05+11%3A46%3A16',
            'CONTACT.EMAIL' => 'spencor.hopkin%40spryker.com',
            'FRONTEND.RESPONSE_URL' => 'https%3A%2F%2Frequestloggerbin.herokuapp.com%2Fbin%2F34f4daed-dba9-42d3-bda1-c7d9d392aac4',
            'ACCOUNT.BRAND' => 'EASYCREDIT',
            'PROCESSING.STATUS.CODE' => '90',
            'NAME.GIVEN' => 'Ralf',
            'CRITERION.EASYCREDIT_FIRSTRATEDUEDATE' => '1554069600000',
            'IDENTIFICATION.SHORTID' => '4132.9357.6750',
            'ADDRESS.CITY' => 'N%C3%BCrnberg',
            'CLEARING.AMOUNT' => '2173.50',
            'CRITERION.EASYCREDIT_EFFECTIVEINTEREST' => '9.20',
            'PROCESSING.CODE' => 'HP.IN.90.00',
            'PROCESSING.STATUS' => 'NEW',
            'SECURITY.SENDER' => '31HA07BC8142C5A171745D00AD63D182',
            'CRITERION.EASYCREDIT_NOMINALINTEREST' => '8.83',
            'USER.LOGIN' => '31ha07bc8142c5a171744e5aef11ffd3',
            'USER.PWD' => '93167DE7',
            'PROCESSING.RETURN.CODE' => '000.100.112',
            'CRITERION.PAYMENT_METHOD' => 'EasyCreditPaymentMethod',
            'PROCESSING.RESULT' => 'ACK',
            'CLEARING.CURRENCY' => 'EUR',
            'CRITERION.EASYCREDIT_LASTRATEDUEDATE' => '1598911200000',
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.UNIQUEID' => '31HA07BC814A66978BC91789A6112944',
            'CRITERION.SECRET' => '1813871baedc67b57df45cb95e383fde7aa636f773ecbb394a41e3cc4eeabb3bcf0cff8d3ddb5a2ff5b9cc0feaecb54a15ccb5f8e4c28045563dd38f3e028365',
            'PRESENTATION.CURRENCY' => 'EUR',
            'PROCESSING.REASON.CODE' => '00',
            'lang' => 'DE',
            'ADDRESS.ZIP' => '90471',
            'CRITERION.EASYCREDIT_ACCRUINGINTEREST' => '154.41',
            'CLEARING.DESCRIPTOR' => '4132.9357.6750+1593-Standard-Test-Merchant+',
            'CRITERION.EASYCREDIT_MONTHLYRATEAMOUNT' => '130.00',
            'CRITERION.EASYCREDIT_TOTALRUNTIME' => '18',
            'CRITERION.EASYCREDIT_TOTALAMOUNT' => '2327.91',
            'CRITERION.EASYCREDIT_AMORTISATIONTEXT' => 'Tilgungsplan%3A+Laufzeit+18+Monate%2C+monatliche+Rate+130%2C00+EUR+%28erste+Rate%3A+114%2C01+EUR+Tilgung%2C+15%2C99+EUR+Zinsen%29%2C+Schlussrate+117%2C91+EUR+%28116%2C99+EUR+Tilgung%2C+0%2C92+EUR+Zinsen%29.+Alternativer+Tilgungsplan%3A+Laufzeit+15+Monate%2C+monatliche+Rate+154%2C00+EUR+%28erste+Rate%3A+138%2C01+EUR+Tilgung%2C+15%2C99+EUR+Zinsen%29%2C+Schlussrate+147%2C35+EUR+%28146%2C22+EUR+Tilgung%2C+1%2C13+EUR+Zinsen%29.',
            'PROCESSING.REASON' => 'SUCCESSFULL',
            'PROCESSING.RETURN' => 'Request+successfully+processed+in+%27Merchant+in+Connector+Test+Mode%27',
            'TRANSACTION.CHANNEL' => '31HA07BC810DCA126FA83FA533886979',
            'FRONTEND.LANGUAGE' => 'DE',
            'PAYMENT.CODE' => 'HP.IN',
            'CRITERION.EASYCREDIT_DEVICEIDENTTOKEN' => '26232cbf-df05-49a9-a700-4fd8383be2bb',
        ];

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
