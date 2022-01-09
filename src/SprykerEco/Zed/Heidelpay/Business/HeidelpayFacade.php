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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory getFactory()
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface getRepository()
 */
class HeidelpayFacade extends AbstractFacade implements HeidelpayFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function postSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): CheckoutResponseTransfer
    {
        return $this->getFactory()
            ->createPostSaveHook()
            ->execute($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $externalResponse
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalPaymentResponse(array $externalResponse): HeidelpayPaymentProcessingResponseTransfer
    {
        return $this->getFactory()
            ->createExternalResponseTransactionHandler()
            ->processExternalPaymentResponse($externalResponse);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $externalResponse
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalEasyCreditPaymentResponse(
        array $externalResponse
    ): HeidelpayPaymentProcessingResponseTransfer {
        return $this->getFactory()
            ->createEasyCreditInitializeExternalResponseTransactionHandler()
            ->processExternalEasyCreditPaymentResponse($externalResponse);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function authorizePayment(OrderTransfer $orderTransfer): void
    {
        $this->getFactory()
            ->createAuthorizeTransactionHandler()
            ->authorize($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorizeOnRegistrationPayment(OrderTransfer $orderTransfer): HeidelpayResponseTransfer
    {
        return $this->getFactory()
            ->createAuthorizeOnRegistrationTransactionHandler()
            ->authorizeOnRegistration($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function initializePayment(QuoteTransfer $quoteTransfer): HeidelpayResponseTransfer
    {
        return $this->getFactory()
            ->createInitializeTransactionHandler()
            ->initialize($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function debitPayment(OrderTransfer $orderTransfer): void
    {
        $this->getFactory()
            ->createDebitTransactionHandler()
            ->debit($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function executeDebitOnRegistration(OrderTransfer $orderTransfer): void
    {
        $this->getFactory()
            ->createDebitOnRegistrationTransactionHandler()
            ->debitOnRegistration($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function finalizePayment(OrderTransfer $orderTransfer): void
    {
        $this->getFactory()
            ->createFinalizeTransactionHandler()
            ->finalize($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function executePaymentReservation(OrderTransfer $orderTransfer): void
    {
        $this->getFactory()
            ->createReservationTransactionHandler()
            ->executeReservation($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function executeRefund(OrderTransfer $orderTransfer): void
    {
        $this->getFactory()
            ->createRefundTransactionHandler()
            ->executeRefund($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function capturePayment(OrderTransfer $orderTransfer): void
    {
        $this->getFactory()
            ->createCaptureTransactionHandler()
            ->capture($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->getFactory()
            ->createOrderSaver()
            ->saveOrderPayment($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentTransfer
     */
    public function getPaymentByIdSalesOrder(int $idSalesOrder): HeidelpayPaymentTransfer
    {
        return $this->getFactory()
            ->createPaymentReader()
            ->getPaymentByIdSalesOrder($idSalesOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayAuthorizeTransactionLogRequestTransfer $authorizeLogRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    public function getAuthorizeTransactionLog(HeidelpayAuthorizeTransactionLogRequestTransfer $authorizeLogRequestTransfer): HeidelpayTransactionLogTransfer
    {
        return $this->getFactory()
            ->createTransactionLogReader()
            ->findOrderAuthorizeTransactionLogByOrderReference($authorizeLogRequestTransfer->getOrderReference());
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer
     */
    public function getCreditCardPaymentOptions(QuoteTransfer $quoteTransfer): HeidelpayCreditCardPaymentOptionsTransfer
    {
        return $this->getFactory()
            ->createPaymentOptionsCalculator()
            ->getCreditCardPaymentOptions($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitPaymentOptionsTransfer
     */
    public function getDirectDebitPaymentOptions(QuoteTransfer $quoteTransfer): HeidelpayDirectDebitPaymentOptionsTransfer
    {
        return $this->getFactory()
            ->createDirectDebitPaymentOptionsCalculator()
            ->getDirectDebitPaymentOptions($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer
     */
    public function saveCreditCardRegistration(HeidelpayRegistrationRequestTransfer $registrationRequestTransfer): HeidelpayRegistrationSaveResponseTransfer
    {
        return $this->getFactory()
            ->createCreditCardRegistrationSaver()
            ->saveCreditCardRegistration($registrationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function saveDirectDebitRegistration(
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        return $this->getFactory()
            ->createDirectDebitRegistrationWriter()
            ->createDirectDebitRegistration($directDebitRegistrationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer
     */
    public function findCreditCardRegistrationByIdAndQuote(
        HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
    ): HeidelpayCreditCardRegistrationTransfer {
        return $this->getFactory()
            ->createCreditCardRegistrationReader()
            ->findCreditCardRegistrationByIdAndQuote(
                $findRegistrationRequestTransfer->getIdRegistration(),
                $findRegistrationRequestTransfer->getQuote(),
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function retrieveDirectDebitRegistration(
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        return $this->getFactory()
            ->createDirectDebitRegistrationReader()
            ->retrieveDirectDebitRegistration($directDebitRegistrationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer): PaymentMethodsTransfer
    {
        return $this->getFactory()
            ->createPaymentMethodFilter()
            ->filterPaymentMethods($paymentMethodsTransfer, $quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function processNotification(HeidelpayNotificationTransfer $notificationTransfer): HeidelpayNotificationTransfer
    {
        return $this->getFactory()
            ->createHeidelpayNotificationProcessor()
            ->processNotification($notificationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function isSalesOrderCaptureApproved(int $idSalesOrder): bool
    {
        return $this->getFactory()
            ->createSalesOrderChecker()
            ->isCaptureApproved($idSalesOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function isSalesOrderRefunded(int $idSalesOrder): bool
    {
        return $this->getFactory()
            ->createSalesOrderChecker()
            ->isRefunded($idSalesOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function isSalesOrderDebitOnRegistrationCompleted(int $idSalesOrder): bool
    {
        return $this->getFactory()
            ->createSalesOrderChecker()
            ->isDebitOnRegistrationCompleted($idSalesOrder);
    }
}
