<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Heidelpay;

use Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
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
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer
     */
    public function heidelpayEasycreditRequest(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createZedStub()
            ->easycreditRequest($quoteTransfer);
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
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer
     */
    public function saveCreditCardRegistration(HeidelpayRegistrationRequestTransfer $registrationRequestTransfer)
    {
        return $this->getFactory()
            ->createZedStub()
            ->saveCreditCardRegistration($registrationRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idRegistration
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer|null
     */
    public function findRegistrationByIdAndQuote($idRegistration, QuoteTransfer $quoteTransfer)
    {
        $findRegistrationRequestTransfer = $this->buildFindRegistrationRequestTransfer($idRegistration, $quoteTransfer);

        return $this->getFactory()
            ->createZedStub()
            ->findCreditCardRegistrationByIdAndQuote($findRegistrationRequestTransfer);
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $responseArray
     *
     * @return array
     */
    public function filterResponseParameters(array $responseArray)
    {
        return array_filter($responseArray, function ($key) {
            return !preg_match('/^paymentForm+|^lang+/', $key);
        }, ARRAY_FILTER_USE_KEY);
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

    /**
     * @param int $idRegistration
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer
     */
    protected function buildFindRegistrationRequestTransfer($idRegistration, QuoteTransfer $quoteTransfer)
    {
        return (new HeidelpayRegistrationByIdAndQuoteRequestTransfer())
            ->setIdRegistration($idRegistration)
            ->setQuote($quoteTransfer);
    }
}
