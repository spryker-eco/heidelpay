<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Heidelpay\Zed;

use Generated\Shared\Transfer\HeidelpayAuthorizeTransactionLogRequestTransfer;
use Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class HeidelpayStub extends ZedRequestStub implements HeidelpayStubInterface
{
    const ZED_EASYCREDIT_REQUEST = '/heidelpay/gateway/easycredit-request';
    const ZED_GET_AUTHORIZE_TRANSACTION_LOG = '/heidelpay/gateway/get-authorize-transaction-log';
    const ZED_GET_AUTHORIZE_ON_REGISTRATION_TRANSACTION_LOG = '/heidelpay/gateway/get-authorize-on-registration-transaction-log';
    const ZED_GET_CREDIT_CARD_PAYMENT_OPTIONS = '/heidelpay/gateway/get-credit-card-payment-options';
    const ZED_GET_PROCESS_EXTERNAL_PAYMENT_RESPONSE = '/heidelpay/gateway/process-external-payment-response';
    const ZED_GET_PROCESS_EXTERNAL_EASY_CREDIT_PAYMENT_RESPONSE = '/heidelpay/gateway/process-external-easy-credit-payment-response';
    const ZED_GET_SAVE_CREDIT_CARD_REGISTRATION = '/heidelpay/gateway/save-credit-card-registration';
    const ZED_GET_FIND_CREDIT_CARD_REGISTRATION = '/heidelpay/gateway/find-credit-card-registration';

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    public function getAuthorizeTransactionLogByOrderReference($orderReference)
    {
        /** @var \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $responseTransfer */
        $responseTransfer = $this->zedStub->call(
            static::ZED_GET_AUTHORIZE_TRANSACTION_LOG,
            $this->createAuthorizeTransactionLogRequestByOrderReference($orderReference)
        );

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer
     */
    public function getCreditCardPaymentOptions(QuoteTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer $responseTransfer */
        $responseTransfer = $this->zedStub->call(
            static::ZED_GET_CREDIT_CARD_PAYMENT_OPTIONS,
            $quoteTransfer
        );

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer $externalPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalPaymentResponse(
        HeidelpayExternalPaymentRequestTransfer $externalPaymentRequestTransfer
    ) {
        /** @var \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer $responseTransfer */
        $responseTransfer = $this->zedStub->call(
            static::ZED_GET_PROCESS_EXTERNAL_PAYMENT_RESPONSE,
            $externalPaymentRequestTransfer
        );

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer $externalPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalEasyCreditPaymentResponse(HeidelpayExternalPaymentRequestTransfer $externalPaymentRequestTransfer)
    {
        /** @var \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer $responseTransfer */
        $responseTransfer = $this->zedStub->call(
            static::ZED_GET_PROCESS_EXTERNAL_EASY_CREDIT_PAYMENT_RESPONSE,
            $externalPaymentRequestTransfer
        );

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer
     */
    public function saveCreditCardRegistration(
        HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
    ) {
        /** @var \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer $responseTransfer */
        $responseTransfer = $this->zedStub->call(
            static::ZED_GET_SAVE_CREDIT_CARD_REGISTRATION,
            $registrationRequestTransfer
        );

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer
     */
    public function findCreditCardRegistrationByIdAndQuote(
        HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
    ) {
        /** @var \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer $responseTransfer */
        $responseTransfer = $this->zedStub->call(
            static::ZED_GET_FIND_CREDIT_CARD_REGISTRATION,
            $findRegistrationRequestTransfer
        );

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer
     */
    public function easycreditRequest(QuoteTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $responseTransfer */
        $responseTransfer = $this->zedStub->call(
            static::ZED_EASYCREDIT_REQUEST,
            $quoteTransfer
        );

        return $responseTransfer;
    }

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\HeidelpayAuthorizeTransactionLogRequestTransfer
     */
    protected function createAuthorizeTransactionLogRequestByOrderReference($orderReference)
    {
        /** @var \Generated\Shared\Transfer\HeidelpayAuthorizeTransactionLogRequestTransfer $authorizeTransactionLogRequestTransfer */
        $authorizeTransactionLogRequestTransfer = new HeidelpayAuthorizeTransactionLogRequestTransfer();
        $authorizeTransactionLogRequestTransfer->setOrderReference($orderReference);

        return $authorizeTransactionLogRequestTransfer;
    }
}
