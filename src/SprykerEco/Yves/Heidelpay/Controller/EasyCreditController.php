<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Controller;

use ArrayObject;
use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayFactory getFactory()
 * @method \SprykerEco\Client\Heidelpay\HeidelpayClientInterface getClient()
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayConfig getConfig()
 */
class EasyCreditController extends BaseHeidelpayController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function easyCreditPaymentAction(Request $request): Response
    {
        if (!$request->isMethod(Request::METHOD_POST)) {
            throw new NotFoundHttpException();
        }

        $responseArray = $this->getUrldecodedRequestBody($request);
        $processingResultTransfer = $this->processEasyCreditPaymentResponse(
            $this->getClient()->filterResponseParameters($responseArray),
        );

        if ($processingResultTransfer->getIsError()) {
            return new Response($this->getFailurePageUrl($processingResultTransfer));
        }

        $redirectUrl = $this->generateEasyCreditRedirectUrl($responseArray);

        return new Response($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function easyCreditInitializePaymentAction(Request $request): RedirectResponse
    {
        $quoteTransfer = $this->getClient()->getQuote();
        $paymentParameters = $request->query->all();

        $this->getFactory()
            ->createEasyCreditResponseToQuoteHydrator()
            ->hydrateQuoteTransferWithEasyCreditResponse($paymentParameters, $quoteTransfer);

        return $this->redirectResponseExternal(
            $this->getConfig()->getYvesCheckoutSummaryStepUrl(),
        );
    }

    /**
     * @param array<string> $requestParameters
     *
     * @return array<string>
     */
    protected function getPaymentParametersFromEasyCreditResponseParameters(array $requestParameters): array
    {
        $paymentParameters = new ArrayObject();
        $this->getFactory()
            ->createEasyCreditResponseToGetParametersMapper()
            ->map($requestParameters, $paymentParameters);

        return $paymentParameters->getArrayCopy();
    }

    /**
     * @param array<string> $requestAsArray
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function hydrateEasyCreditResponseToQuote(array $requestAsArray, QuoteTransfer $quoteTransfer): void
    {
        $this->getFactory()
            ->createEasyCreditResponseToQuoteHydrator()
            ->hydrateQuoteTransferWithEasyCreditResponse($requestAsArray, $quoteTransfer);
    }

    /**
     * @param array<string> $requestArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    protected function processEasyCreditPaymentResponse(array $requestArray): HeidelpayPaymentProcessingResponseTransfer
    {
        return $this
            ->getClient()
            ->processExternalEasyCreditPaymentResponse($requestArray);
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
            $processingResultTransfer->getError()->getCode(),
        );
    }

    /**
     * @param array<string> $responseArray
     *
     * @return string
     */
    protected function generateEasyCreditRedirectUrl(array $responseArray): string
    {
        return Url::generate(
            $this->getConfig()->getYvesInitializePaymentUrl(),
            $this->getPaymentParametersFromEasyCreditResponseParameters($responseArray),
        )->build();
    }
}
