<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\RefundNotSupportedException;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\RefundTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithRefundInterface;

class RefundTransactionHandler implements RefundTransactionHandlerInterface
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE_REFUND_TRANSACTION_NOT_SUPPORTED =
        'Attempt to call refund transaction on payment method \'%s\' ' .
        'that does not support it';

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\RefundTransactionInterface
     */
    protected $transaction;

    /**
     * @var array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithRefundInterface>
     */
    protected $paymentMethodAdapterCollection;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface
     */
    protected $heidelpayRequestBuilder;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\RefundTransactionInterface $transaction
     * @param array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithRefundInterface> $paymentMethodAdapterCollection
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface $heidelpayRequestBuilder
     */
    public function __construct(
        RefundTransactionInterface $transaction,
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
    public function executeRefund(OrderTransfer $orderTransfer): void
    {
        $refundRequestTransfer = $this->heidelpayRequestBuilder->buildRefundRequestFromOrder($orderTransfer);
        $paymentAdapter = $this->getPaymentMethodAdapter($orderTransfer);
        $this->transaction->executeTransaction($refundRequestTransfer, $paymentAdapter);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\RefundNotSupportedException
     *
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithRefundInterface
     */
    protected function getPaymentMethodAdapter(OrderTransfer $orderTransfer): PaymentWithRefundInterface
    {
        $paymentMethodCode = $orderTransfer->getHeidelpayPayment()->getPaymentMethod();

        if (!isset($this->paymentMethodAdapterCollection[$paymentMethodCode])) {
            throw new RefundNotSupportedException(
                sprintf(static::ERROR_MESSAGE_REFUND_TRANSACTION_NOT_SUPPORTED, $paymentMethodCode),
            );
        }

        return $this->paymentMethodAdapterCollection[$paymentMethodCode];
    }
}
