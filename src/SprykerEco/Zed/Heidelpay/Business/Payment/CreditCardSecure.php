<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay;
use Spryker\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationWriterInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReaderInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithPreSavePaymentInterface;
use Spryker\Zed\Heidelpay\HeidelpayConfig;

class CreditCardSecure extends BaseHeidelpayPaymentMethod implements
    PaymentWithPostSaveOrderInterface,
    PaymentWithPreSavePaymentInterface
{

    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationWriterInterface
     */
    private $registrationWriter;

    /**
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReaderInterface $transactionLogManager
     * @param \Spryker\Zed\Heidelpay\HeidelpayConfig $config
     * @param \Spryker\Zed\Heidelpay\Business\Payment\CreditCard\Registration\RegistrationWriterInterface $registrationWriter
     */
    public function __construct(
        TransactionLogReaderInterface $transactionLogManager,
        HeidelpayConfig $config,
        RegistrationWriterInterface $registrationWriter
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
    public function postSaveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $authorizeTransactionLogTransfer = $this->findOrderAuthorizeTransactionLog(
            $checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder()
        );

        if (($authorizeTransactionLogTransfer !== null) &&
            $this->isAuthorizeTransactionSentSuccessfully($authorizeTransactionLogTransfer)
        ) {
            $this->saveCreditCardRegistration($quoteTransfer);
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
    public function addDataToPayment(SpyPaymentHeidelpay $paymentEntity, QuoteTransfer $quoteTransfer)
    {
        $registrationId = $this->getRegistrationIdFromQuote($quoteTransfer);
        $paymentEntity->setIdPaymentRegistration($registrationId);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function saveCreditCardRegistration(QuoteTransfer $quoteTransfer)
    {
        $this->registrationWriter->saveRegistrationFromQuote($quoteTransfer);
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
            ->getHeidelpayCreditCardSecure()
            ->getSelectedRegistration()
            ->getRegistrationNumber();
    }

}
