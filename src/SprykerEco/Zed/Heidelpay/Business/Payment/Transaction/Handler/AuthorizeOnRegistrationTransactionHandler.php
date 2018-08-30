<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderauthorizeOnRegistration;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeOnRegistrationTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\AuthorizeOnRegistrationNotSupportedException;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilder;

class AuthorizeOnRegistrationTransactionHandler implements AuthorizeOnRegistrationTransactionHandlerInterface
{
    const ERROR_MESSAGE_AUTHORIZE_ON_REGISTRATION_TRANSACTION_NOT_SUPPORTED =
        'Attempt to call authorize on registration transaction on payment method \'%s\' ' .
        'that does not support it';
    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeOnRegistrationTransactionInterface
     */
    protected $transaction;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeOnRegistrationInterface[]
     */
    protected $paymentMethodAdapterCollection;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilder
     */
    protected $heidelpayRequestBuilder;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeOnRegistrationTransactionInterface $transaction
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeOnRegistrationInterface[] $paymentMethodAdapterCollection
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilder $heidelpayRequestBuilder
     */
    public function __construct(
        AuthorizeOnRegistrationTransactionInterface $transaction,
        array $paymentMethodAdapterCollection,
        AdapterRequestFromQuoteBuilder $heidelpayRequestBuilder
    ) {
        $this->transaction = $transaction;
        $this->paymentMethodAdapterCollection = $paymentMethodAdapterCollection;
        $this->heidelpayRequestBuilder = $heidelpayRequestBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $orderTransfer
     *
     * @return void
     */
    public function authorizeOnRegistration(QuoteTransfer $quoteTransfer)
    {
        $authorizeOnRegistrationRequestTransfer = $this->buildAuthorizeOnRegistrationRequest($quoteTransfer);
        $paymentAdapter = $this->getPaymentMethodAdapter($quoteTransfer);
        return $this->transaction->executeTransaction($authorizeOnRegistrationRequestTransfer, $paymentAdapter);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function buildAuthorizeOnRegistrationRequest(QuoteTransfer $quoteTransfer)
    {
        $authorizeRequestTransfer = $this->heidelpayRequestBuilder->buildEasyCreditRequest($quoteTransfer);

        return $authorizeRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\AuthorizeOnRegistrationNotSupportedException
     *
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeOnRegistrationInterface
     */
    protected function getPaymentMethodAdapter(QuoteTransfer $quoteTransfer)
    {
        $paymentMethodCode = $this->getPaymentMethodCode($quoteTransfer);

        if (!isset($this->paymentMethodAdapterCollection[$paymentMethodCode])) {
            throw new AuthorizeOnRegistrationNotSupportedException(
                sprintf(static::ERROR_MESSAGE_AUTHORIZE_ON_REGISTRATION_TRANSACTION_NOT_SUPPORTED, $paymentMethodCode)
            );
        }

        return $this->paymentMethodAdapterCollection[$paymentMethodCode];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getPaymentMethodCode(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getPayment()->getPaymentMethod();
    }
}
