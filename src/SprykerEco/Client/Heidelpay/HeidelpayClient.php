<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Heidelpay;

use Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerEco\Client\Heidelpay\HeidelpayFactory getFactory()
 */
class HeidelpayClient extends AbstractClient implements HeidelpayClientInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $errorCode
     *
     * @return string
     */
    public function translateErrorMessageByCode($errorCode)
    {
        $currentLocale = $this->getCurrentLocale();

        return $this->getFactory()
            ->createHeidelpayApiAdapter()
            ->getTranslatedMessageByCode($errorCode, $currentLocale);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    public function getAuthorizeTransactionLogForOrder($orderReference)
    {
        return $this->getFactory()
            ->createZedStub()
            ->getAuthorizeTransactionLogByOrderReference($orderReference);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuoteFromSession()
    {
        return $this->getFactory()
            ->getQuoteClient()
            ->getQuote();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $externalResponse
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer
     */
    public function parseExternalResponse(array $externalResponse)
    {
        return $this->getFactory()
            ->createExternalResponseValidator()
            ->parseExternalResponse($externalResponse);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $externalResponse
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalPaymentResponse(array $externalResponse)
    {
        $externalResponseTransfer = $this->buildTransferFromExternalResponseArray($externalResponse);

        return $this->getFactory()
            ->createZedStub()
            ->processExternalPaymentResponse($externalResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer
     */
    public function getCreditCardPaymentOptions(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createZedStub()
            ->getCreditCardPaymentOptions($quoteTransfer);
    }

    /**
     * @return string
     */
    protected function getCurrentLocale()
    {
        return $this->getFactory()
            ->getLocaleClient()
            ->getCurrentLocale();
    }

    /**
     * @param array $externalResponse
     *
     * @return \Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer
     */
    protected function buildTransferFromExternalResponseArray(array $externalResponse)
    {
        return (new HeidelpayExternalPaymentRequestTransfer())
            ->setBody($externalResponse);
    }

}
