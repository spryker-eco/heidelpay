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
use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer;
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $externalResponse
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalEasyCreditPaymentResponse(array $externalResponse);

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorizeOnRegistrationPayment(OrderTransfer $orderTransfer);

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function finalizePayment(OrderTransfer $orderTransfer);

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function reservationPayment(OrderTransfer $orderTransfer);

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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function initializePayment(QuoteTransfer $quoteTransfer);

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
    public function getPaymentByIdSalesOrder(int $idSalesOrder);

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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer
     */
    public function saveCreditCardRegistration(HeidelpayRegistrationRequestTransfer $registrationRequestTransfer): HeidelpayRegistrationSaveResponseTransfer;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer
     */
    public function findCreditCardRegistrationByIdAndQuote(HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer): HeidelpayCreditCardRegistrationTransfer;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer);
}
