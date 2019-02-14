<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Controller;

use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayFactory getFactory()
 * @method \SprykerEco\Client\Heidelpay\HeidelpayClientInterface getClient()
 */
class EasyCreditController extends BaseHeidelpayController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function easyCreditPaymentAction(Request $request)
    {
        if (!$request->isMethod(Request::METHOD_POST)) {
            return new Response('', 400);
        }

        $paymentParameters = $this->getUrldecodedRequestBody($request);
        $processingResultTransfer = $this->processEasyCreditPaymentResponse(
            $this->getClient()->filterResponseParameters($paymentParameters)
        );

        if ($processingResultTransfer->getIsError()) {
            echo $this->getFailurePageUrl($processingResultTransfer);
            exit();
        }

        $paymentInitializeParameters = $this->getFactory()
            ->createEasyCreditResponseToGetParametersMapper()
            ->getMapped($paymentParameters, []);

        $redirectUrl = Url::generate(
            $this->getInitializePaymentUrl(),
            $paymentInitializeParameters
        )->build();

        echo $redirectUrl;
        exit();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function easyCreditInitializePaymentAction(Request $request)
    {
        $quoteTransfer = $this->getClient()->getQuoteFromSession();
        $paymentParameters = $request->query->all();

        $this->getFactory()
            ->createEasyCreditResponseToQuoteHydrator()
            ->hydrateEasyCreditResponseToQuote($paymentParameters, $quoteTransfer);

        return $this->redirectResponseInternal(
            $this->getSummaryPageUrl()
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

    /**
     * @return string
     */
    protected function getSummaryPageUrl(): string
    {
        return $this->getConfig()->getYvesCheckoutSummaryStepUrl();
    }

    /**
     * @return string
     */
    protected function getInitializePaymentUrl(): string
    {
        return $this->getConfig()->getYvesInitializePaymentUrl();
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer $processingResultTransfer
     *
     * @return string
     */
    protected function getFailurePageUrl(HeidelpayPaymentProcessingResponseTransfer $processingResultTransfer): string
    {
        return sprintf(
            $this->getConfig()->getYvesCheckoutPaymentFailedUrl(),
            $processingResultTransfer->getError()->getCode()
        );
    }
}
