<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Heidelpay\Controller;

use Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\Heidelpay\HeidelpayFactory getFactory()
 * @method \Spryker\Client\Heidelpay\HeidelpayClientInterface getClient()
 */
class CreditCardController extends BaseHeidelpayController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function registerResponseAction(Request $request)
    {
        $requestAsArray = $this->getUrldecodedRequestBody($request);
        $processingResultTransfer = $this->processRegistrationResponse($requestAsArray);

        return $this->streamResponse($processingResultTransfer);
    }

    /**
     * @param array $requestArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer
     */
    protected function processRegistrationResponse(array $requestArray)
    {
        return $this->getFactory()
            ->createRegistrationResponseHandler()
            ->handleRegistrationResponse($requestArray);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer $processingResultTransfer
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function streamResponse(HeidelpayRegistrationResponseTransfer $processingResultTransfer)
    {
        $callback = function () use ($processingResultTransfer) {
            echo $this->getCustomerRedirectUrl($processingResultTransfer);
        };

        return $this->streamedResponse($callback);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer $processingResultTransfer
     *
     * @return string
     */
    protected function getCustomerRedirectUrl(HeidelpayRegistrationResponseTransfer $processingResultTransfer)
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
        return $this->getConfig()->getYvesCheckoutSummaryStepUrl();
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer $responseTransfer
     *
     * @return string
     */
    protected function getFailureRedirectUrl(HeidelpayRegistrationResponseTransfer $responseTransfer)
    {
        return sprintf(
            $this->getConfig()->getYvesCheckoutPaymentFailedUrl(),
            $responseTransfer->getError()->getCode()
        );
    }

}
