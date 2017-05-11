<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransactionInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\Exception\CaptureNotSupportedException;

class CaptureTransactionHandler implements CaptureTransactionHandlerInterface
{

    const ERROR_MESSAGE_CAPTURE_TRANSACTION_NOT_SUPPORTED =
        'Attempt to call capture transaction on payment method \'%s\' ' .
        'that does not support it';

    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransactionInterface
     */
    protected $transaction;

    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface[]
     */
    protected $paymentMethodAdapterCollection;

    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface
     */
    protected $heidelpayRequestBuilder;

    /**
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransactionInterface $transaction
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface[] $paymentMethodAdapterCollection
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface $heidelpayRequestBuilder
     */
    public function __construct(
        CaptureTransactionInterface $transaction,
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
    public function capture(OrderTransfer $orderTransfer)
    {
        $captureRequestTransfer = $this->buildCaptureRequest($orderTransfer);
        $paymentAdapter = $this->getPaymentMethodAdapter($orderTransfer);
        $this->transaction->executeTransaction($captureRequestTransfer, $paymentAdapter);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function buildCaptureRequest(OrderTransfer $orderTransfer)
    {
        $captureRequestTransfer = $this->heidelpayRequestBuilder->buildCaptureRequestFromOrder($orderTransfer);

        return $captureRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Spryker\Zed\Heidelpay\Business\Payment\Transaction\Exception\CaptureNotSupportedException
     *
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface
     */
    protected function getPaymentMethodAdapter(OrderTransfer $orderTransfer)
    {
        $paymentMethodCode = $this->getPaymentMethodCode($orderTransfer);

        if (!isset($this->paymentMethodAdapterCollection[$paymentMethodCode])) {
            throw new CaptureNotSupportedException(
                sprintf(static::ERROR_MESSAGE_CAPTURE_TRANSACTION_NOT_SUPPORTED, $paymentMethodCode)
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
