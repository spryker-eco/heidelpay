<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Controller;

use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayFactory getFactory()
 * @method \SprykerEco\Client\Heidelpay\HeidelpayClientInterface getClient()
 */
class HeidelpayController extends BaseHeidelpayController
{

    const PARAM_ERROR_CODE = 'error_code';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function paymentFailedAction(Request $request)
    {
        $errorCode = $request->get(static::PARAM_ERROR_CODE, '');

        $redirectResponseWithErrorTransfer = $this->getFactory()
            ->createPaymentFailureHandler()
            ->handlePaymentFailureByErrorCode($errorCode);

        $this->addErrorMessage($redirectResponseWithErrorTransfer->getErrorMessage());

        return new RedirectResponse($redirectResponseWithErrorTransfer->getRedirectUrl());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function paymentAction(Request $request)
    {
        if (!$request->isMethod(Request::METHOD_POST)) {
            throw new NotFoundHttpException();
        }

        $requestAsArray = $this->getUrldecodedRequestBody($request);
        $processingResultTransfer = $this->processPaymentResponse($requestAsArray);

        return $this->streamResponse($processingResultTransfer);
    }

    /**
     * @param array $requestArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    protected function processPaymentResponse(array $requestArray)
    {
        return $this
            ->getClient()
            ->processExternalPaymentResponse($requestArray);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer $processingResultTransfer
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function streamResponse(HeidelpayPaymentProcessingResponseTransfer $processingResultTransfer)
    {
        $callback = function () use ($processingResultTransfer) {
            echo $this->getCustomerRedirectUrl($processingResultTransfer);
        };

        return $this->streamedResponse($callback);
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

}
