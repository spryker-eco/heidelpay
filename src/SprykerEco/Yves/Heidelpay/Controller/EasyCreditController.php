<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayFactory getFactory()
 * @method \SprykerEco\Client\Heidelpay\HeidelpayClientInterface getClient()
 */
class EasyCreditController extends BaseHeidelpayController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function easyCreditPaymentAction(Request $request)
    {
        if (!$request->isMethod(Request::METHOD_POST)) {
            //throw new NotFoundHttpException();
        }

        $quoteTransfer = $this->getClient()->getQuoteFromSession();
        //$requestAsArray = $this->getUrldecodedRequestBody($request);
        $requestAsArray = array(
            'NAME_FAMILY' => 'Ratentyp',
            'CRITERION_SDK_NAME' => 'Heidelpay\\PhpPaymentApi',
            'IDENTIFICATION_TRANSACTIONID' => '70147edd16a09f7410f3e8e835eda21f3a2a30c1',
            'CRITERION_EASYCREDIT_TOTALORDERAMOUNT' => '272.62',
            'CRITERION_EASYCREDIT_MONTHLYRATECOUNT' => '15',
            'ADDRESS_COUNTRY' => 'DE',
            'ADDRESS_STREET' => 'Vangerowstr 18',
            'FRONTEND_ENABLED' => 'TRUE',
            'PRESENTATION_AMOUNT' => '272.62',
            'CRITERION_EASYCREDIT_UUID' => '147ba471-a7b9-45ee-b3d8-69bff12a7ba2',
            'TRANSACTION_MODE' => 'CONNECTOR_TEST',
            'CONTACT_IP' => '91.232.178.22',
            'CRITERION_EASYCREDIT_PRECONTRACTINFORMATIONURL' => 'https://ratenkauf.easycredit.de/payment/#/b956f157.0904144744EMZqYUvCDaQho1CPVuoIgrHv/vorvertraglicheinfo',
            'CRITERION_SDK_VERSION' => 'v1.6.0',
            'CRITERION_EASYCREDIT_LASTRATEAMOUNT' => '7.61',
            'PROCESSING_TIMESTAMP' => '2018-09-04 12:47:43',
            'CONTACT_EMAIL' => 'pugach.ivan@gmail.com',
            'FRONTEND_RESPONSE_URL' => 'https://requestloggerbin.herokuapp.com/bin/c1b0bfc8-1e29-4e49-a857-1c3a8f739bcc',
            'REQUEST_VERSION' => '1.0',
            'ACCOUNT_BRAND' => 'EASYCREDIT',
            'PROCESSING_STATUS_CODE' => '90',
            'NAME_GIVEN' => 'Ralle',
            'CRITERION_EASYCREDIT_FIRSTRATEDUEDATE' => '1541026800000',
            'IDENTIFICATION_SHORTID' => '3999.9166.3196',
            'ADDRESS_CITY' => 'Heidelberg',
            'CLEARING_AMOUNT' => '272.62',
            'CRITERION_EASYCREDIT_EFFECTIVEINTEREST' => '8.78',
            'PROCESSING_CODE' => 'HP.IN.90.00',
            'PROCESSING_STATUS' => 'NEW',
            'SECURITY_SENDER' => '31HA07BC8142C5A171745D00AD63D182',
            'CRITERION_EASYCREDIT_NOMINALINTEREST' => '8.45',
            'USER_LOGIN' => '31ha07bc8142c5a171744e5aef11ffd3',
            'USER_PWD' => '93167DE7',
            'PROCESSING_RETURN_CODE' => '000.100.11',
            'CRITERION_PAYMENT_METHOD' => 'EasyCreditPaymentMethod',
            'PROCESSING_RESULT' => 'ACK',
            'CLEARING_CURRENCY' => 'EUR',
            'CRITERION_EASYCREDIT_LASTRATEDUEDATE' => '1577833200000',
            'FRONTEND_MODE' => 'WHITELABEL',
            'IDENTIFICATION_UNIQUEID' => '31HA07BC813DD629DD2B3C9E86F102FF',
            'CRITERION_SECRET' => 'e35cad34251305b76564cc84247fe795ca7c049057319ef33f526140fcc307789ac1350c6d61466658516e7997358333ade4b0407e20daf5458ecff33ced1892',
            'PRESENTATION_CURRENCY' => 'EUR',
            'NAME_COMPANY' => 'Spryker',
            'PROCESSING_REASON_CODE' => '00',
            'lang' => 'DE',
            'ADDRESS_ZIP' => '69115',
            'CRITERION_EASYCREDIT_ACCRUINGINTEREST' => '14.99',
            'CLEARING_DESCRIPTOR' => '3999.9166.3196 1593-HPC TEST-Merchant direkt ',
            'CRITERION_EASYCREDIT_MONTHLYRATEAMOUNT' => '20.00',
            'CRITERION_EASYCREDIT_TOTALRUNTIME' => '15',
            'CRITERION_EASYCREDIT_TOTALAMOUNT' => '287.61',
            'CRITERION_EASYCREDIT_AMORTISATIONTEXT' => 'Tilgungsplan: Laufzeit 15 Monate, monatliche Rate 20,00 EUR (erste Rate: 18,08 EUR Tilgung, 1,92 EUR Zinsen), Schlussrate 7,61 EUR (7,58 EUR Tilgung, 0,03 EUR Zinsen). Alternativer Tilgungsplan: Laufzeit 12 Monate, monatliche Rate 24,00 EUR (erste Rate: 22,08 EUR Tilgung, 1,92 EUR Zinsen), Schlussrate 21,14 EUR (20,99 EUR Tilgung, 0,15 EUR Zinsen).',
            'PROCESSING_REASON' => 'SUCCESSFULL',
            'PROCESSING_RETURN' => 'Request successfully processed in \'Merchant in Connector Test Mode\'',
            'TRANSACTION_CHANNEL' => '31HA07BC810DCA126FA83FA533886979',
            'FRONTEND_LANGUAGE' => 'DE',
            'PAYMENT_CODE' => 'HP.IN',
            'CRITERION_EASYCREDIT_DEVICEIDENTTOKEN' => 'f48343e0-81bc-445c-8da4-d192d1ccb089'
        );

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
            : $this->getSuccessRedirectUrl();
    }

    /**
     * @return string
     */
    protected function getSuccessRedirectUrl()
    {
        return $this->getConfig()->getYvesCheckoutSuccessUrl();
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer $processingResultTransfer
     *
     * @return string
     */
    protected function getFailureRedirectUrl(HeidelpayPaymentProcessingResponseTransfer $processingResultTransfer)
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
    protected function hydrateEasyCreditResponseToQuote(array $requestAsArray, QuoteTransfer $quoteTransfer) {
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
