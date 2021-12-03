<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay\Zed;

use Generated\Shared\Transfer\HeidelpayAuthorizeTransactionLogRequestTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer;
use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class HeidelpayStub extends ZedRequestStub implements HeidelpayStubInterface
{
    /**
     * @var string
     */
    protected const ZED_GET_AUTHORIZE_TRANSACTION_LOG = '/heidelpay/gateway/get-authorize-transaction-log';
    /**
     * @var string
     */
    protected const ZED_GET_CREDIT_CARD_PAYMENT_OPTIONS = '/heidelpay/gateway/get-credit-card-payment-options';
    /**
     * @var string
     */
    protected const ZED_GET_DIRECT_DEBIT_PAYMENT_OPTIONS = '/heidelpay/gateway/get-direct-debit-payment-options';
    /**
     * @var string
     */
    protected const ZED_GET_PROCESS_EXTERNAL_PAYMENT_RESPONSE = '/heidelpay/gateway/process-external-payment-response';
    /**
     * @var string
     */
    protected const ZED_GET_SAVE_CREDIT_CARD_REGISTRATION = '/heidelpay/gateway/save-credit-card-registration';
    /**
     * @var string
     */
    protected const ZED_GET_SAVE_DIRECT_DEBIT_REGISTRATION = '/heidelpay/gateway/save-direct-debit-registration';
    /**
     * @var string
     */
    protected const ZED_GET_FIND_CREDIT_CARD_REGISTRATION = '/heidelpay/gateway/find-credit-card-registration';
    /**
     * @var string
     */
    protected const ZED_GET_RETRIEVE_DIRECT_DEBIT_REGISTRATION = '/heidelpay/gateway/retrieve-direct-debit-registration';
    /**
     * @var string
     */
    protected const ZED_SEND_EASYCREDIT_INITIALIZE_REQUEST = '/heidelpay/gateway/easycredit-initialize-payment';
    /**
     * @var string
     */
    protected const ZED_GET_AUTHORIZE_ON_REGISTRATION_TRANSACTION_LOG = '/heidelpay/gateway/get-authorize-on-registration-transaction-log';
    /**
     * @var string
     */
    protected const ZED_GET_PROCESS_EXTERNAL_EASY_CREDIT_PAYMENT_RESPONSE = '/heidelpay/gateway/process-external-easy-credit-payment-response';
    /**
     * @var string
     */
    protected const ZED_PROCESS_NOTIFICATION = '/heidelpay/gateway/process-notification';

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    public function getAuthorizeTransactionLogByOrderReference(string $orderReference): HeidelpayTransactionLogTransfer
    {
        /** @var \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $heidelpayTransactionLogTransfer */
        $heidelpayTransactionLogTransfer = $this->zedStub->call(
            static::ZED_GET_AUTHORIZE_TRANSACTION_LOG,
            $this->createAuthorizeTransactionLogRequestByOrderReference($orderReference)
        );

        return $heidelpayTransactionLogTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer
     */
    public function getCreditCardPaymentOptions(QuoteTransfer $quoteTransfer): HeidelpayCreditCardPaymentOptionsTransfer
    {
        /** @var \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer $heidelpayCreditCardPaymentOptionsTransfer */
        $heidelpayCreditCardPaymentOptionsTransfer = $this->zedStub->call(
            static::ZED_GET_CREDIT_CARD_PAYMENT_OPTIONS,
            $quoteTransfer
        );

        return $heidelpayCreditCardPaymentOptionsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer
     */
    public function getDirectDebitPaymentOptions(QuoteTransfer $quoteTransfer): HeidelpayDirectDebitPaymentOptionsTransfer
    {
        /** @var \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer $heidelpayDirectDebitPaymentOptionsTransfer */
        $heidelpayDirectDebitPaymentOptionsTransfer = $this->zedStub->call(
            static::ZED_GET_DIRECT_DEBIT_PAYMENT_OPTIONS,
            $quoteTransfer
        );

        return $heidelpayDirectDebitPaymentOptionsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer $externalPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalPaymentResponse(
        HeidelpayExternalPaymentRequestTransfer $externalPaymentRequestTransfer
    ): HeidelpayPaymentProcessingResponseTransfer {
        /** @var \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer $heidelpayPaymentProcessingResponseTransfer */
        $heidelpayPaymentProcessingResponseTransfer = $this->zedStub->call(
            static::ZED_GET_PROCESS_EXTERNAL_PAYMENT_RESPONSE,
            $externalPaymentRequestTransfer
        );

        return $heidelpayPaymentProcessingResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer $externalPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalEasyCreditPaymentResponse(
        HeidelpayExternalPaymentRequestTransfer $externalPaymentRequestTransfer
    ): HeidelpayPaymentProcessingResponseTransfer {
        /** @var \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer $heidelpayPaymentProcessingResponseTransfer */
        $heidelpayPaymentProcessingResponseTransfer = $this->zedStub->call(
            static::ZED_GET_PROCESS_EXTERNAL_EASY_CREDIT_PAYMENT_RESPONSE,
            $externalPaymentRequestTransfer
        );

        return $heidelpayPaymentProcessingResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer
     */
    public function saveCreditCardRegistration(
        HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
    ): HeidelpayRegistrationSaveResponseTransfer {
        /** @var \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer $heidelpayRegistrationSaveResponseTransfer */
        $heidelpayRegistrationSaveResponseTransfer = $this->zedStub->call(
            static::ZED_GET_SAVE_CREDIT_CARD_REGISTRATION,
            $registrationRequestTransfer
        );

        return $heidelpayRegistrationSaveResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function saveDirectDebitRegistration(
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        /** @var \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer */
        $directDebitRegistrationTransfer = $this->zedStub->call(
            static::ZED_GET_SAVE_DIRECT_DEBIT_REGISTRATION,
            $directDebitRegistrationTransfer
        );

        return $directDebitRegistrationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer|null
     */
    public function findCreditCardRegistrationByIdAndQuote(
        HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
    ): ?HeidelpayCreditCardRegistrationTransfer {
        /** @var \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer $heidelpayCreditCardRegistrationTransfer */
        $heidelpayCreditCardRegistrationTransfer = $this->zedStub->call(
            static::ZED_GET_FIND_CREDIT_CARD_REGISTRATION,
            $findRegistrationRequestTransfer
        );

        return $heidelpayCreditCardRegistrationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function retrieveDirectDebitRegistration(
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        /** @var \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer */
        $directDebitRegistrationTransfer = $this->zedStub->call(
            static::ZED_GET_RETRIEVE_DIRECT_DEBIT_REGISTRATION,
            $directDebitRegistrationTransfer
        );

        return $directDebitRegistrationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function sendEasycreditInitializeRequest(QuoteTransfer $quoteTransfer): HeidelpayResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\HeidelpayResponseTransfer $heidelpayResponseTransfer */
        $heidelpayResponseTransfer = $this->zedStub->call(
            static::ZED_SEND_EASYCREDIT_INITIALIZE_REQUEST,
            $quoteTransfer
        );

        return $heidelpayResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function processNotification(HeidelpayNotificationTransfer $notificationTransfer): HeidelpayNotificationTransfer
    {
        /** @var \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer */
        $notificationTransfer = $this->zedStub->call(
            static::ZED_PROCESS_NOTIFICATION,
            $notificationTransfer
        );

        return $notificationTransfer;
    }

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\HeidelpayAuthorizeTransactionLogRequestTransfer
     */
    protected function createAuthorizeTransactionLogRequestByOrderReference(
        string $orderReference
    ): HeidelpayAuthorizeTransactionLogRequestTransfer {
        $authorizeTransactionLogRequestTransfer = new HeidelpayAuthorizeTransactionLogRequestTransfer();
        $authorizeTransactionLogRequestTransfer->setOrderReference($orderReference);

        return $authorizeTransactionLogRequestTransfer;
    }
}
