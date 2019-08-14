<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Request;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig as SharedHeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Business\Mapper\QuoteToHeidelpayRequestInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyFacadeInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayConfig;

class AdapterRequestFromQuoteBuilder extends BaseAdapterRequestBuilder implements AdapterRequestFromQuoteBuilderInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Mapper\QuoteToHeidelpayRequestInterface
     */
    protected $quoteToHeidelpayMapper;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Mapper\QuoteToHeidelpayRequestInterface $quoteToHeidelpayMapper
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyFacadeInterface $currencyFacade
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfig $config
     */
    public function __construct(
        QuoteToHeidelpayRequestInterface $quoteToHeidelpayMapper,
        HeidelpayToCurrencyFacadeInterface $currencyFacade,
        HeidelpayConfig $config
    ) {
        parent::__construct($currencyFacade, $config);
        $this->quoteToHeidelpayMapper = $quoteToHeidelpayMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function buildCreditCardRegistrationRequest(QuoteTransfer $quoteTransfer): HeidelpayRequestTransfer
    {
        $registrationRequestTransfer = $this->buildBaseQuoteHeidelpayRequest($quoteTransfer);
        $this->setCreditCardTransactionChannel($registrationRequestTransfer);
        $this->setYvesUrlForAsyncIframeResponse($registrationRequestTransfer);

        return $registrationRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function buildDirectDebitRegistrationRequest(QuoteTransfer $quoteTransfer): HeidelpayRequestTransfer
    {
        $registrationRequestTransfer = $this->buildBaseQuoteHeidelpayRequest($quoteTransfer);
        $this->setDirectDebitTransactionChannel($registrationRequestTransfer);
        $this->setYvesUrlForAsyncDirectDebitResponse($registrationRequestTransfer);

        return $registrationRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function buildEasyCreditRequest(QuoteTransfer $quoteTransfer): HeidelpayRequestTransfer
    {
        return $this->buildBaseQuoteHeidelpayRequest($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return void
     */
    protected function setCreditCardTransactionChannel(HeidelpayRequestTransfer $heidelpayRequestTransfer): void
    {
        $paymentMethod = SharedHeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE;
        $this->hydrateTransactionChannel($heidelpayRequestTransfer, $paymentMethod);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return void
     */
    protected function setDirectDebitTransactionChannel(HeidelpayRequestTransfer $heidelpayRequestTransfer): void
    {
        $paymentMethod = SharedHeidelpayConfig::PAYMENT_METHOD_DIRECT_DEBIT;
        $this->hydrateTransactionChannel($heidelpayRequestTransfer, $paymentMethod);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function buildBaseQuoteHeidelpayRequest(QuoteTransfer $quoteTransfer): HeidelpayRequestTransfer
    {
        $requestTransfer = new HeidelpayRequestTransfer();

        $requestTransfer = $this->hydrateQuote($requestTransfer, $quoteTransfer);
        $requestTransfer = $this->hydrateRequestData($requestTransfer);

        $paymentMethod = $quoteTransfer->getPayment()->getPaymentMethod();

        $requestTransfer = $this->hydrateTransactionChannel($requestTransfer, $paymentMethod);

        return $requestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function hydrateQuote(HeidelpayRequestTransfer $heidelpayRequestTransfer, QuoteTransfer $quoteTransfer): HeidelpayRequestTransfer
    {
        $this->quoteToHeidelpayMapper->map($quoteTransfer, $heidelpayRequestTransfer);

        return $heidelpayRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $requestTransfer
     *
     * @return void
     */
    protected function setYvesUrlForAsyncIframeResponse(HeidelpayRequestTransfer $requestTransfer): void
    {
        $requestTransfer->getAsync()->setResponseUrl(
            $this->config->getYvesUrlForAsyncIframeResponse()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $requestTransfer
     *
     * @return void
     */
    protected function setYvesUrlForAsyncDirectDebitResponse(HeidelpayRequestTransfer $requestTransfer): void
    {
        $requestTransfer->getAsync()->setResponseUrl(
            $this->config->getYvesUrlForAsyncDirectDebitResponse()
        );
    }
}
