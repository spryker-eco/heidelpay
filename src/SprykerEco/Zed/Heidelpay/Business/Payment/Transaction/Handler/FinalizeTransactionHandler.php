<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\FinalizeNotSupportedException;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\FinalizeTransactionInterface;

class FinalizeTransactionHandler implements FinalizeTransactionHandlerInterface
{
    public const ERROR_MESSAGE_FINALIZE_TRANSACTION_NOT_SUPPORTED =
        'Attempt to call finalize transaction on payment method \'%s\' ' .
        'that does not support it';

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\FinalizeTransactionInterface
     */
    protected $transaction;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithFinalizeInterface[]
     */
    protected $paymentMethodAdapterCollection;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface
     */
    protected $heidelpayRequestBuilder;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface
     */
    protected $paymentWriter;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\FinalizeTransactionInterface $transaction
     * @param array $paymentMethodAdapterCollection
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface $heidelpayRequestBuilder
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface $paymentWriter
     */
    public function __construct(
        FinalizeTransactionInterface $transaction,
        array $paymentMethodAdapterCollection,
        AdapterRequestFromOrderBuilderInterface $heidelpayRequestBuilder,
        PaymentWriterInterface $paymentWriter
    ) {
        $this->transaction = $transaction;
        $this->paymentMethodAdapterCollection = $paymentMethodAdapterCollection;
        $this->heidelpayRequestBuilder = $heidelpayRequestBuilder;
        $this->paymentWriter = $paymentWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function finalize(OrderTransfer $orderTransfer)
    {
        $finalizeRequestTransfer = $this->buildFinalizeRequest($orderTransfer);
        $paymentAdapter = $this->getPaymentMethodAdapter($orderTransfer);

        $finalizeResponseTransfer = $this->transaction->executeTransaction($finalizeRequestTransfer, $paymentAdapter);

        if ($finalizeResponseTransfer->getIdTransactionUnique() === null) {
            return;
        }

        $this->paymentWriter->updatePaymentReferenceByIdSalesOrder(
            $finalizeResponseTransfer->getIdTransactionUnique(),
            $orderTransfer->getIdSalesOrder()
        );
        $orderTransfer->getHeidelpayPayment()->setIdPaymentReference(
            $finalizeResponseTransfer->getIdTransactionUnique()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function buildFinalizeRequest(OrderTransfer $orderTransfer)
    {
        $finalizeRequestTransfer = $this->heidelpayRequestBuilder->buildFinalizeRequestFromOrder($orderTransfer);

        return $finalizeRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\FinalizeNotSupportedException
     *
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithFinalizeInterface
     */
    protected function getPaymentMethodAdapter(OrderTransfer $orderTransfer)
    {
        $paymentMethodCode = $this->getPaymentMethodCode($orderTransfer);

        if (!isset($this->paymentMethodAdapterCollection[$paymentMethodCode])) {
            throw new FinalizeNotSupportedException(
                sprintf(static::ERROR_MESSAGE_FINALIZE_TRANSACTION_NOT_SUPPORTED, $paymentMethodCode)
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
