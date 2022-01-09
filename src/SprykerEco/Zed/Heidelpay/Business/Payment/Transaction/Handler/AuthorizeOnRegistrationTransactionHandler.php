<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeOnRegistrationTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\AuthorizeOnRegistrationNotSupportedException;

class AuthorizeOnRegistrationTransactionHandler implements AuthorizeOnRegistrationTransactionHandlerInterface
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE_AUTHORIZE_ON_REGISTRATION_TRANSACTION_NOT_SUPPORTED =
        'Attempt to call authorize on registration transaction on payment method \'%s\' ' .
        'that does not support it';

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeOnRegistrationTransactionInterface
     */
    protected $transaction;

    /**
     * @var array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeOnRegistrationInterface>
     */
    protected $paymentMethodAdapterCollection;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface
     */
    protected $heidelpayRequestBuilder;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeOnRegistrationTransactionInterface $transaction
     * @param array $paymentMethodAdapterCollection
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface $heidelpayRequestBuilder
     */
    public function __construct(
        AuthorizeOnRegistrationTransactionInterface $transaction,
        array $paymentMethodAdapterCollection,
        AdapterRequestFromOrderBuilderInterface $heidelpayRequestBuilder
    ) {
        $this->transaction = $transaction;
        $this->paymentMethodAdapterCollection = $paymentMethodAdapterCollection;
        $this->heidelpayRequestBuilder = $heidelpayRequestBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorizeOnRegistration(OrderTransfer $orderTransfer): HeidelpayResponseTransfer
    {
        $authorizeOnRegistrationRequestTransfer = $this->buildAuthorizeOnRegistrationRequest($orderTransfer);
        $paymentAdapter = $this->getPaymentMethodAdapter($orderTransfer);

        return $this->transaction->executeTransaction($authorizeOnRegistrationRequestTransfer, $paymentAdapter);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function buildAuthorizeOnRegistrationRequest(OrderTransfer $orderTransfer)
    {
        $authorizeRequestTransfer = $this->heidelpayRequestBuilder->buildAuthorizeOnRegistrationRequestFromOrder($orderTransfer);

        return $authorizeRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\AuthorizeOnRegistrationNotSupportedException
     *
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeOnRegistrationInterface
     */
    protected function getPaymentMethodAdapter(OrderTransfer $orderTransfer)
    {
        $paymentMethodCode = $this->getPaymentMethodCode($orderTransfer);

        if (!isset($this->paymentMethodAdapterCollection[$paymentMethodCode])) {
            throw new AuthorizeOnRegistrationNotSupportedException(
                sprintf(static::ERROR_MESSAGE_AUTHORIZE_ON_REGISTRATION_TRANSACTION_NOT_SUPPORTED, $paymentMethodCode),
            );
        }

        return $this->paymentMethodAdapterCollection[$paymentMethodCode];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function getPaymentMethodCode(OrderTransfer $orderTransfer)
    {
        return $orderTransfer->getHeidelpayPayment()->getPaymentMethod();
    }
}
