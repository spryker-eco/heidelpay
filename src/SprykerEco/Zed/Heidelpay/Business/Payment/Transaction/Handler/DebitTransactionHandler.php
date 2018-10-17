<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReaderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\DebitTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\DebitNotSupportedException;

class DebitTransactionHandler implements DebitTransactionHandlerInterface
{
    const ERROR_MESSAGE_DEBIT_TRANSACTION_NOT_SUPPORTED =
        'Attempt to call debit transaction on payment method \'%s\' ' .
        'that does not support it';

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\DebitTransactionInterface
     */
    protected $transaction;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface[]
     */
    protected $paymentMethodAdapterCollection;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface
     */
    protected $heidelpayRequestBuilder;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReaderInterface
     */
    protected $paymentReader;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\DebitTransactionInterface $transaction
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface[] $paymentMethodAdapterCollection
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface $heidelpayRequestBuilder
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReaderInterface $paymentReader
     */
    public function __construct(
        DebitTransactionInterface $transaction,
        array $paymentMethodAdapterCollection,
        AdapterRequestFromOrderBuilderInterface $heidelpayRequestBuilder,
        PaymentReaderInterface $paymentReader
    ) {
        $this->transaction = $transaction;
        $this->paymentMethodAdapterCollection = $paymentMethodAdapterCollection;
        $this->heidelpayRequestBuilder = $heidelpayRequestBuilder;
        $this->paymentReader = $paymentReader;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function debit(OrderTransfer $orderTransfer)
    {
        $debitRequestTransfer = $this->buildDebitRequest($orderTransfer);
        $paymentAdapter = $this->getPaymentMethodAdapter($orderTransfer);
        $this->transaction->executeTransaction($debitRequestTransfer, $paymentAdapter);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function buildDebitRequest(OrderTransfer $orderTransfer)
    {
        $debitRequestTransfer = $this->heidelpayRequestBuilder->buildDebitRequestFromOrder($orderTransfer);

        return $debitRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\DebitNotSupportedException
     *
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface
     */
    protected function getPaymentMethodAdapter(OrderTransfer $orderTransfer)
    {
        $paymentMethodCode = $this->getPaymentMethodCode($orderTransfer);

        if (!isset($this->paymentMethodAdapterCollection[$paymentMethodCode])) {
            throw new DebitNotSupportedException(
                sprintf(static::ERROR_MESSAGE_DEBIT_TRANSACTION_NOT_SUPPORTED, $paymentMethodCode)
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
