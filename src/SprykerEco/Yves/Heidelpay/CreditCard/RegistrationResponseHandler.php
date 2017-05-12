<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\CreditCard;

use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Client\Heidelpay\HeidelpayClientInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface;

class RegistrationResponseHandler implements RegistrationResponseHandlerInterface
{

    /**
     * @var \SprykerEco\Client\Heidelpay\HeidelpayClientInterface
     */
    protected $heidelpayClient;

    /**
     * @var \SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface
     */
    protected $heidelpayPaymentHandler;

    /**
     * @param \SprykerEco\Client\Heidelpay\HeidelpayClientInterface $heidelpayClient
     * @param \SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface $heidelpayPaymentHandler
     */
    public function __construct(
        HeidelpayClientInterface $heidelpayClient,
        HeidelpayHandlerInterface $heidelpayPaymentHandler
    ) {

        $this->heidelpayClient = $heidelpayClient;
        $this->heidelpayPaymentHandler = $heidelpayPaymentHandler;
    }

    /**
     * @param array $responseArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer
     */
    public function handleRegistrationResponse(array $responseArray)
    {
        $registrationResponseTransfer = $this->convertResponseArrayToTransfer($responseArray);
        $this->setPaymentDataToSession($registrationResponseTransfer);

        return $registrationResponseTransfer;
    }

    /**
     * @param array $responseArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer
     */
    protected function convertResponseArrayToTransfer(array $responseArray)
    {
        return $this->heidelpayClient->parseExternalResponse($responseArray);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer $registrationResponse
     *
     * @return void
     */
    protected function setPaymentDataToSession(HeidelpayRegistrationResponseTransfer $registrationResponse)
    {
        $quoteTransfer = $this->heidelpayClient->getQuoteFromSession();
        $this->addCreditCardPaymentSelection($registrationResponse, $quoteTransfer);

        $this->heidelpayPaymentHandler->addPaymentToQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer $registrationResponse
     * @param \Generated\Shared\Transfer\QuoteTransfer $quote
     *
     * @return void
     */
    protected function addCreditCardPaymentSelection(
        HeidelpayRegistrationResponseTransfer $registrationResponse,
        QuoteTransfer $quote
    ) {
        $creditCardRegistrationTransfer = (new HeidelpayCreditCardRegistrationTransfer())
            ->setCreditCardInfo($registrationResponse->getCreditCardInfo())
            ->setRegistrationNumber($registrationResponse->getIdRegistration());

        $paymentObject = $quote->getPayment();

        $paymentObject->setPaymentSelection(HeidelpayConstants::PAYMENT_METHOD_CREDIT_CARD_SECURE);

        $paymentObject->getHeidelpayCreditCardSecure()
            ->setSelectedRegistration($creditCardRegistrationTransfer)
            ->setSelectedPaymentOption(HeidelpayConstants::PAYMENT_OPTION_NEW_REGISTRATION);
    }

}
