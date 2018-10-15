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
        $requestAsArray = $this->getUrldecodedRequestBody($request);

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
