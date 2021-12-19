<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Controller;

use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayFactory getFactory()
 * @method \SprykerEco\Client\Heidelpay\HeidelpayClientInterface getClient()
 */
class HeidelpayController extends BaseHeidelpayController
{
    /**
     * @var string
     */
    public const PARAM_ERROR_CODE = 'error_code';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function paymentFailedAction(Request $request): RedirectResponse
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
    public function paymentAction(Request $request): StreamedResponse
    {
        if (!$request->isMethod(Request::METHOD_POST)) {
            throw new NotFoundHttpException();
        }

        $processingResultTransfer = $this->getFactory()
            ->createHeidelpayPaymentResponseProcessor()
            ->processPaymentResponse($request);

        return $this->streamResponse($processingResultTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer $processingResultTransfer
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function streamResponse(HeidelpayPaymentProcessingResponseTransfer $processingResultTransfer): StreamedResponse
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
    protected function getCustomerRedirectUrl(HeidelpayPaymentProcessingResponseTransfer $processingResultTransfer): string
    {
        return $processingResultTransfer->getIsError()
            ? $this->getFailureRedirectUrl($processingResultTransfer)
            : $this->getSuccessRedirectUrl();
    }

    /**
     * @return string
     */
    protected function getSuccessRedirectUrl(): string
    {
        return $this->getConfig()->getYvesCheckoutSuccessUrl();
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
            $processingResultTransfer->getError()->getCode(),
        );
    }
}
