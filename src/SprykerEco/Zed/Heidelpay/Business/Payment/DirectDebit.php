<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay;
use SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\Registration\DirectDebitRegistrationWriterInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReaderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPreSavePaymentInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayConfig;

class DirectDebit extends BaseHeidelpayPaymentMethod implements
    PaymentWithPostSaveOrderInterface,
    PaymentWithPreSavePaymentInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\Registration\DirectDebitRegistrationWriterInterface
     */
    protected $registrationWriter;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReaderInterface $transactionLogManager
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfig $config
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\DirectDebit\Registration\DirectDebitRegistrationWriterInterface $registrationWriter
     */
    public function __construct(
        TransactionLogReaderInterface $transactionLogManager,
        HeidelpayConfig $config,
        DirectDebitRegistrationWriterInterface $registrationWriter
    ) {
        parent::__construct($transactionLogManager, $config);

        $this->registrationWriter = $registrationWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function postSaveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        $authorizeTransactionLogTransfer = $this->findOrderAuthorizeTransactionLog(
            $checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder()
        );

        if ($this->isAuthorizeTransactionSentSuccessfully($authorizeTransactionLogTransfer) &&
            $this->hasCustomerRegisteredShipmentAddress($quoteTransfer->getShippingAddress())
        ) {
            $this->updateRegistrationWithAddressId($quoteTransfer);
        }

        $redirectUrl = $this->getCheckoutRedirectUrlFromAuthorizeTransactionLog(
            $checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder()
        );

        $this->setExternalRedirect($redirectUrl, $checkoutResponseTransfer);
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay $paymentEntity
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function addDataToPayment(SpyPaymentHeidelpay $paymentEntity, QuoteTransfer $quoteTransfer): void
    {
        $registrationId = $this->getRegistrationIdFromQuote($quoteTransfer);
        $paymentEntity->setIdPaymentRegistration($registrationId);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $shippingAddress
     *
     * @return bool
     */
    protected function hasCustomerRegisteredShipmentAddress(AddressTransfer $shippingAddress): bool
    {
        return $shippingAddress->getIdCustomerAddress() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function updateRegistrationWithAddressId(QuoteTransfer $quoteTransfer): void
    {
        $this->registrationWriter->updateDirectDebitRegistration($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getRegistrationIdFromQuote(QuoteTransfer $quoteTransfer): string
    {
        return $quoteTransfer
            ->getPayment()
            ->getHeidelpayDirectDebit()
            ->getSelectedRegistration()
            ->getIdDirectDebitRegistration();
    }
}
