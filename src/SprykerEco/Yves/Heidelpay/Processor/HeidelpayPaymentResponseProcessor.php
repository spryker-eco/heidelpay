<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Processor;

use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use SprykerEco\Client\Heidelpay\HeidelpayClientInterface;
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface;
use Symfony\Component\HttpFoundation\Request;

class HeidelpayPaymentResponseProcessor implements HeidelpayPaymentResponseProcessorInterface
{
    protected const RESPONSE_PARAMETERS_FILTER_PATTERN = '/^paymentForm+|^lang+/';

    /**
     * @var \SprykerEco\Client\Heidelpay\HeidelpayClientInterface
     */
    protected $heidelpayClient;

    /**
     * @var \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \SprykerEco\Client\Heidelpay\HeidelpayClientInterface $heidelpayClient
     * @param \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface $quoteClient
     */
    public function __construct(
        HeidelpayClientInterface $heidelpayClient,
        HeidelpayToQuoteClientInterface $quoteClient
    ) {
        $this->heidelpayClient = $heidelpayClient;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processPaymentResponse(Request $request): HeidelpayPaymentProcessingResponseTransfer
    {
        $requestAsArray = $this->getUrlDecodedRequestBody($request);
        $processingResultTransfer = $this->heidelpayClient
            ->processExternalPaymentResponse(
                $this->filterResponseParameters($requestAsArray)
            );

        if ($processingResultTransfer->getConnectorInvoiceAccountInfo() === null) {
            return $processingResultTransfer;
        }

        $this->updateQuoteWithConnectorInfo($processingResultTransfer);

        return $processingResultTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer $processingResultTransfer
     *
     * @return void
     */
    protected function updateQuoteWithConnectorInfo(HeidelpayPaymentProcessingResponseTransfer $processingResultTransfer): void
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        if ($quoteTransfer->getHeidelpayPayment() === null) {
            return;
        }

        $quoteTransfer->getHeidelpayPayment()
            ->setConnectorInvoiceAccountInfo(
                $processingResultTransfer->getConnectorInvoiceAccountInfo()
            );

        $this->quoteClient->setQuote($quoteTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getUrlDecodedRequestBody(Request $request): array
    {
        $allRequestParameters = $request->request->all();

        foreach ($allRequestParameters as $key => $value) {
            if (is_string($value)) {
                $allRequestParameters[$key] = urldecode($value);
            }
        }

        return $allRequestParameters;
    }

    /**
     * @param string[] $responseArray
     *
     * @return string[]
     */
    public function filterResponseParameters(array $responseArray): array
    {
        return array_filter($responseArray, function ($key) {
            return !preg_match(static::RESPONSE_PARAMETERS_FILTER_PATTERN, $key);
        }, ARRAY_FILTER_USE_KEY);
    }
}
