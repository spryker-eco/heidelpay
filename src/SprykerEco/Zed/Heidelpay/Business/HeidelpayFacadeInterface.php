<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\HeidelpayAuthorizeTransactionLogRequestTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface HeidelpayFacadeInterface
{
    /**
     * Specification:
     *  - Executes a post save hook for the following payment methods:
     *    Sofort / authorize: checks for an external redirect URL in transaction log and redirects customer to the payment system
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function postSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): CheckoutResponseTransfer;

    /**
     * Specification:
     *  - Builds external response transfer based on body from $externalResponse array.
     *  - Executes EasyCreditInitializeExternalResponseTransaction on $externalResponseTransfer.
     *
     * @api
     *
     * @param array $externalResponse
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalEasyCreditPaymentResponse(array $externalResponse): HeidelpayPaymentProcessingResponseTransfer;

    /**
     * Specification:
     *  - Builds authorization request transfer.
     *  - Executes registration transaction.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorizeOnRegistrationPayment(OrderTransfer $orderTransfer): HeidelpayResponseTransfer;

    /**
     * Specification:
     *  - Builds finalize request based on $orderTransfer.
     *  - Executes finalize transaction.
     *  - Updates payment reference by sales order id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function finalizePayment(OrderTransfer $orderTransfer): void;

    /**
     * Specification:
     *  - Builds reservation request based on $orderTransfer.
     *  - Executes reservation transaction.
     *  - Updates payment reference by sales order id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function executePaymentReservation(OrderTransfer $orderTransfer): void;

    /**
     * Specification:
     *  - Builds refund request based on `OrderTransfer`.
     *  - Executes refund transaction.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function executeRefund(OrderTransfer $orderTransfer): void;

    /**
     * Specification:
     *  - Prepares the request for ExternalResponseTransaction
     *  - Executes ExternalResponseTransaction and returns the results
     *
     * @api
     *
     * @param array $externalResponse
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalPaymentResponse(array $externalResponse): HeidelpayPaymentProcessingResponseTransfer;

    /**
     * Specification:
     *  - Send Initialization Request (HP.INI) to EasyCredit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function initializePayment(QuoteTransfer $quoteTransfer): HeidelpayResponseTransfer;

    /**
     * Specification:
     * - Sends payment authorize request to Heidelpay gateway.
     * - Saves the transaction result in DB for future recognition
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function authorizePayment(OrderTransfer $orderTransfer): void;

    /**
     * Specification:
     * - Sends payment debit request to Heidelpay gateway.
     * - Saves the transaction result in DB for future recognition
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function debitPayment(OrderTransfer $orderTransfer): void;

    /**
     * Specification:
     * - Sends payment debit on registration request to Heidelpay gateway.
     * - Saves the transaction result in DB for future recognition.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function executeDebitOnRegistration(OrderTransfer $orderTransfer): void;

    /**
     * Specification:
     * - Sends payment capture request to Heidelpay gateway.
     * - Saves the transaction result in DB for future recognition
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function capturePayment(OrderTransfer $orderTransfer): void;

    /**
     * Specification:
     * - Saves order payment method data according to quote and checkout response transfer data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void;

    /**
     * Specification:
     * - Fetches order payment by fk_sales_order
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentTransfer
     */
    public function getPaymentByIdSalesOrder(int $idSalesOrder): HeidelpayPaymentTransfer;

    /**
     * Specification:
     * - Fetches authorise transaction for a given order by idSalesOrder
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayAuthorizeTransactionLogRequestTransfer $authorizeLogRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    public function getAuthorizeTransactionLog(HeidelpayAuthorizeTransactionLogRequestTransfer $authorizeLogRequestTransfer): HeidelpayTransactionLogTransfer;

    /**
     * Specification:
     * - Calculates available payment options for Credit Card payment method based on a given quote object
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer
     */
    public function getCreditCardPaymentOptions(QuoteTransfer $quoteTransfer): HeidelpayCreditCardPaymentOptionsTransfer;

    /**
     * Specification:
     * - Calculates available payment options for DirectDebit payment method based on a given quote object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer
     */
    public function getDirectDebitPaymentOptions(QuoteTransfer $quoteTransfer): HeidelpayDirectDebitPaymentOptionsTransfer;

    /**
     * Specification:
     *  - Builds registration entity from $registrationRequestTransfer and save it.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer
     */
    public function saveCreditCardRegistration(HeidelpayRegistrationRequestTransfer $registrationRequestTransfer): HeidelpayRegistrationSaveResponseTransfer;

    /**
     * Specification:
     *  - Creates DirectDebit registration based on registration response and saves it into DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function saveDirectDebitRegistration(
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer;

    /**
     * Specification:
     *  - Fetches credit cart registration transfer by id registration and quote hash.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer
     */
    public function findCreditCardRegistrationByIdAndQuote(
        HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
    ): HeidelpayCreditCardRegistrationTransfer;

    /**
     * Specification:
     *  - Fetches DirectDebit registration transfer by id registration and quote hash.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function retrieveDirectDebitRegistration(
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer;

    /**
     * Specification:
     *  - Filters payment methods: checks if method is Heidelpay`s EasyCredit, if grand total is under limit, if address data is valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer): PaymentMethodsTransfer;

    /**
     * Specification:
     *  - Saves notification.
     *  - Updates order items status depends on notification processing result.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function processNotification(HeidelpayNotificationTransfer $notificationTransfer): HeidelpayNotificationTransfer;
}
