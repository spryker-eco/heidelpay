<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\FinalizeNotSupportedException;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\FinalizeTransactionInterface;

class FinalizeTransactionHandler implements FinalizeTransactionHandlerInterface
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE_FINALIZE_TRANSACTION_NOT_SUPPORTED =
        'Attempt to call finalize transaction on payment method \'%s\' ' .
        'that does not support it';

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\FinalizeTransactionInterface
     */
    protected $transaction;

    /**
     * @var array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithFinalizeInterface>
     */
    protected $paymentMethodAdapterCollection;

    /**
     * @var array<\SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface>
     */
    protected $requestBuilderCollection;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface
     */
    protected $paymentWriter;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\FinalizeTransactionInterface $transaction
     * @param array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithFinalizeInterface> $paymentMethodAdapterCollection
     * @param array<\SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface> $requestBuilderCollection
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface $paymentWriter
     */
    public function __construct(
        FinalizeTransactionInterface $transaction,
        array $paymentMethodAdapterCollection,
        array $requestBuilderCollection,
        PaymentWriterInterface $paymentWriter
    ) {
        $this->transaction = $transaction;
        $this->paymentMethodAdapterCollection = $paymentMethodAdapterCollection;
        $this->requestBuilderCollection = $requestBuilderCollection;
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

        $this->paymentWriter->updateHeidelpayPaymentWithResponse($finalizeResponseTransfer);
        $orderTransfer->getHeidelpayPayment()->setIdPaymentReference(
            $finalizeResponseTransfer->getIdTransactionUnique(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function buildFinalizeRequest(OrderTransfer $orderTransfer)
    {
        $requestBuilder = $this->getPaymentMethodRequestBuilder($orderTransfer);

        return $requestBuilder->buildFinalizeRequestFromOrder($orderTransfer);
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
                sprintf(static::ERROR_MESSAGE_FINALIZE_TRANSACTION_NOT_SUPPORTED, $paymentMethodCode),
            );
        }

        return $this->paymentMethodAdapterCollection[$paymentMethodCode];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\FinalizeNotSupportedException
     *
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface
     */
    protected function getPaymentMethodRequestBuilder(OrderTransfer $orderTransfer)
    {
        $paymentMethodCode = $this->getPaymentMethodCode($orderTransfer);

        if (!isset($this->requestBuilderCollection[$paymentMethodCode])) {
            throw new FinalizeNotSupportedException(
                sprintf(static::ERROR_MESSAGE_FINALIZE_TRANSACTION_NOT_SUPPORTED, $paymentMethodCode),
            );
        }

        return $this->requestBuilderCollection[$paymentMethodCode];
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
