<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeTransactionInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\Exception\AuthorizeNotSupportedException;

class AuthorizeTransactionHandler implements AuthorizeTransactionHandlerInterface
{

    const ERROR_MESSAGE_AUTHORIZE_TRANSACTION_NOT_SUPPORTED =
        'Attempt to call authorize transaction on payment method \'%s\' ' .
        'that does not support it';
    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeTransactionInterface
     */
    protected $transaction;

    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface[]
     */
    protected $paymentMethodAdapterCollection;

    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface
     */
    protected $heidelpayRequestBuilder;

    /**
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Transaction\AuthorizeTransactionInterface $transaction
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface[] $paymentMethodAdapterCollection
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface $heidelpayRequestBuilder
     */
    public function __construct(
        AuthorizeTransactionInterface $transaction,
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
     * @return void
     */
    public function authorize(OrderTransfer $orderTransfer)
    {
        $authorizeRequestTransfer = $this->buildAuthorizeRequest($orderTransfer);
        $paymentAdapter = $this->getPaymentMethodAdapter($orderTransfer);
        $this->transaction->executeTransaction($authorizeRequestTransfer, $paymentAdapter);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function buildAuthorizeRequest(OrderTransfer $orderTransfer)
    {
        $authorizeRequestTransfer = $this->heidelpayRequestBuilder->buildAuthorizeRequestFromOrder($orderTransfer);

        return $authorizeRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Spryker\Zed\Heidelpay\Business\Payment\Transaction\Exception\AuthorizeNotSupportedException
     *
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface
     */
    protected function getPaymentMethodAdapter(OrderTransfer $orderTransfer)
    {
        $paymentMethodCode = $this->getPaymentMethodCode($orderTransfer);

        if (!isset($this->paymentMethodAdapterCollection[$paymentMethodCode])) {
            throw new AuthorizeNotSupportedException(
                sprintf(static::ERROR_MESSAGE_AUTHORIZE_TRANSACTION_NOT_SUPPORTED, $paymentMethodCode)
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
