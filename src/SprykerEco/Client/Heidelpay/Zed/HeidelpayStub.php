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
    const ZED_GET_AUTHORIZE_ON_REGISTRATION_TRANSACTION_LOG = '/heidelpay/gateway/get-authorize-on-registration-transaction-log';
    const ZED_GET_CREDIT_CARD_PAYMENT_OPTIONS = '/heidelpay/gateway/get-credit-card-payment-options';
    const ZED_GET_PROCESS_EXTERNAL_PAYMENT_RESPONSE = '/heidelpay/gateway/process-external-payment-response';
    const ZED_GET_PROCESS_EXTERNAL_EASY_CREDIT_PAYMENT_RESPONSE = '/heidelpay/gateway/process-external-easy-credit-payment-response';
    const ZED_GET_SAVE_CREDIT_CARD_REGISTRATION = '/heidelpay/gateway/save-credit-card-registration';
    const ZED_GET_FIND_CREDIT_CARD_REGISTRATION = '/heidelpay/gateway/find-credit-card-registration';

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getAuthorizeTransactionLogByOrderReference($orderReference)
    {
        return $this->zedStub->call(
            static::ZED_GET_AUTHORIZE_TRANSACTION_LOG,
            $this->createAuthorizeTransactionLogRequestByOrderReference($orderReference)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getCreditCardPaymentOptions(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call(
            static::ZED_GET_CREDIT_CARD_PAYMENT_OPTIONS,
            $quoteTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer $externalPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function processExternalPaymentResponse(
        HeidelpayExternalPaymentRequestTransfer $externalPaymentRequestTransfer
    ) {
        return $this->zedStub->call(
            static::ZED_GET_PROCESS_EXTERNAL_PAYMENT_RESPONSE,
            $externalPaymentRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer $externalPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function processExternalEasyCreditPaymentResponse(HeidelpayExternalPaymentRequestTransfer $externalPaymentRequestTransfer) {
        return $this->zedStub->call(
            static::ZED_GET_PROCESS_EXTERNAL_EASY_CREDIT_PAYMENT_RESPONSE,
            $externalPaymentRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function saveCreditCardRegistration(
        HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
    ) {
        return $this->zedStub->call(
            static::ZED_GET_SAVE_CREDIT_CARD_REGISTRATION,
            $registrationRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer|null
     */
    public function findCreditCardRegistrationByIdAndQuote(
        HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
    ) {
        return $this->zedStub->call(
            static::ZED_GET_FIND_CREDIT_CARD_REGISTRATION,
            $findRegistrationRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function easycreditRequest(QuoteTransfer $quoteTransfer) {
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
    protected function createAuthorizeTransactionLogRequestByOrderReference($orderReference)
    {
        $authorizeTransactionLogRequestTransfer = new HeidelpayAuthorizeTransactionLogRequestTransfer();
        $authorizeTransactionLogRequestTransfer->setOrderReference($orderReference);

        return $authorizeTransactionLogRequestTransfer;
    }
}
