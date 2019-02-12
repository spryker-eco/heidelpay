<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay\Zed;

use Generated\Shared\Transfer\HeidelpayAuthorizeTransactionLogRequestTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer;
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
    protected const ZED_GET_AUTHORIZE_TRANSACTION_LOG = '/heidelpay/gateway/get-authorize-transaction-log';
    protected const ZED_GET_CREDIT_CARD_PAYMENT_OPTIONS = '/heidelpay/gateway/get-credit-card-payment-options';
    protected const ZED_GET_PROCESS_EXTERNAL_PAYMENT_RESPONSE = '/heidelpay/gateway/process-external-payment-response';
    protected const ZED_GET_SAVE_CREDIT_CARD_REGISTRATION = '/heidelpay/gateway/save-credit-card-registration';
    protected const ZED_GET_FIND_CREDIT_CARD_REGISTRATION = '/heidelpay/gateway/find-credit-card-registration';
    protected const ZED_EASYCREDIT_REQUEST = '/heidelpay/gateway/easycredit-initialize-payment';
    protected const ZED_GET_AUTHORIZE_ON_REGISTRATION_TRANSACTION_LOG = '/heidelpay/gateway/get-authorize-on-registration-transaction-log';
    protected const ZED_GET_PROCESS_EXTERNAL_EASY_CREDIT_PAYMENT_RESPONSE = '/heidelpay/gateway/process-external-easy-credit-payment-response';

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    public function getAuthorizeTransactionLogByOrderReference(string $orderReference): HeidelpayTransactionLogTransfer
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
    public function getCreditCardPaymentOptions(QuoteTransfer $quoteTransfer): HeidelpayCreditCardPaymentOptionsTransfer
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
    ): HeidelpayPaymentProcessingResponseTransfer {
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
    public function processExternalEasyCreditPaymentResponse(
        HeidelpayExternalPaymentRequestTransfer $externalPaymentRequestTransfer
    ): HeidelpayPaymentProcessingResponseTransfer {
        return $this->zedStub->call(
            static::ZED_GET_PROCESS_EXTERNAL_EASY_CREDIT_PAYMENT_RESPONSE,
            $externalPaymentRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer
     */
    public function saveCreditCardRegistration(
        HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
    ): HeidelpayRegistrationSaveResponseTransfer {
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
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer|null
     */
    public function findCreditCardRegistrationByIdAndQuote(
        HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
    ): ?HeidelpayCreditCardRegistrationTransfer {
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
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function easycreditRequest(QuoteTransfer $quoteTransfer): HeidelpayResponseTransfer
    {
        return $this->zedStub->call(
            static::ZED_EASYCREDIT_REQUEST,
            $quoteTransfer
        );
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
