<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Processor;

use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayResponseErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerEco\Client\Heidelpay\HeidelpayClientInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Shared\Heidelpay\QuoteUniqueIdGenerator;
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface;
use Symfony\Component\HttpFoundation\Request;

class HeidelpayDirectDebitRegistrationProcessor implements HeidelpayDirectDebitRegistrationProcessorInterface
{
    protected const REQUEST_PARAM_REGISTRATION_ID = 'id_registration';
    protected const ERROR_CODE_REGISTRATION_NOT_FOUND = 'registration_not_found';
    protected const ERROR_CODE_REGISTRATION_NOT_SAVED = 'registration_not_saved';
    protected const ERROR_CODE_QUOTE_EXPIRED = 'quote_expired';

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
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function processNewRegistration(Request $request): HeidelpayDirectDebitRegistrationTransfer
    {
        $directDebitRegistrationTransfer = $this->createNewDirectDebitRegistrationTransferFromRequest($request);
        if ($directDebitRegistrationTransfer->getIsError()) {
            return $directDebitRegistrationTransfer;
        }

        $directDebitRegistrationTransfer = $this->heidelpayClient
            ->saveDirectDebitRegistration($directDebitRegistrationTransfer);

        if ($directDebitRegistrationTransfer->getIdDirectDebitRegistration() !== null) {
            return $directDebitRegistrationTransfer;
        }

        return $this->addError(
            static::ERROR_CODE_REGISTRATION_NOT_SAVED,
            $directDebitRegistrationTransfer
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function processSuccessRegistration(Request $request): HeidelpayDirectDebitRegistrationTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();
        $directDebitRegistrationTransfer = $this->retrieveExistingDirectDebitRegistrationByRequestAndQuote($request, $quoteTransfer);
        if ($this->isQuoteExpired($quoteTransfer)) {
            return $this->addError(
                static::ERROR_CODE_QUOTE_EXPIRED,
                $directDebitRegistrationTransfer
            );
        }

        $directDebitRegistrationTransfer = $this->heidelpayClient
            ->retrieveDirectDebitRegistration($directDebitRegistrationTransfer);

        if ($directDebitRegistrationTransfer->getIdDirectDebitRegistration() === null) {
            return $this->addError(
                static::ERROR_CODE_REGISTRATION_NOT_FOUND,
                $directDebitRegistrationTransfer
            );
        }

        $quoteTransfer = $this->addDirectDebitRegistrationToQuote($directDebitRegistrationTransfer, $quoteTransfer);
        $this->quoteClient->setQuote($quoteTransfer);

        return $directDebitRegistrationTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    protected function createNewDirectDebitRegistrationTransferFromRequest(Request $request): HeidelpayDirectDebitRegistrationTransfer
    {
        $apiResponseAsArray = $this->getUrldecodedRequestBody($request);
        $apiResponseAsArray = $this->filterResponseParameters($apiResponseAsArray);

        return $this->heidelpayClient->parseDirectDebitRegistrationResponse($apiResponseAsArray);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    protected function retrieveExistingDirectDebitRegistrationByRequestAndQuote(
        Request $request,
        QuoteTransfer $quoteTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        $directDebitRegistrationTransfer = (new HeidelpayDirectDebitRegistrationTransfer())
            ->setRegistrationUniqueId($this->getRegistrationId($request))
            ->setIdCustomerAddress($quoteTransfer->getShippingAddress()->getIdCustomerAddress())
            ->setTransactionId($this->generateTransactionId($quoteTransfer));

        return $directDebitRegistrationTransfer;
    }

    /**
     * @param string $code
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    protected function addError(string $code, HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer): HeidelpayDirectDebitRegistrationTransfer
    {
        $directDebitRegistrationTransfer
            ->setIsError(true)
            ->setError(
                (new HeidelpayResponseErrorTransfer())
                    ->setCode($code)
            );

        return $directDebitRegistrationTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    protected function getRegistrationId(Request $request): int
    {
        return (int)$request->get(static::REQUEST_PARAM_REGISTRATION_ID);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteExpired(QuoteTransfer $quoteTransfer): bool
    {
        try {
            $quoteTransfer->requireCustomer();
            $quoteTransfer->getCustomer()->requireEmail();
            $quoteTransfer->requireTotals();
        } catch (RequiredTransferPropertyException $exception) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addDirectDebitRegistrationToQuote(
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $paymentTransfer = $quoteTransfer->requirePayment()->getPayment();
        $paymentTransfer
            ->requireHeidelpayDirectDebit()
            ->getHeidelpayDirectDebit()
            ->setSelectedRegistration($directDebitRegistrationTransfer)
            ->setSelectedPaymentOption(HeidelpayConfig::PAYMENT_OPTION_NEW_REGISTRATION);

        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
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
     * @param array $responseArray
     *
     * @return array
     */
    public function filterResponseParameters(array $responseArray): array
    {
        return array_filter($responseArray, function ($key) {
            return !preg_match('/^paymentForm+|^lang+/', $key);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function generateTransactionId(QuoteTransfer $quoteTransfer): string
    {
        return QuoteUniqueIdGenerator::getHashByCustomerEmailAndTotals($quoteTransfer);
    }
}
