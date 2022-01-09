<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\CaptureNotSupportedException;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface;

class CaptureTransactionHandler implements CaptureTransactionHandlerInterface
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE_CAPTURE_TRANSACTION_NOT_SUPPORTED =
        'Attempt to call capture transaction on payment method \'%s\' ' .
        'that does not support it';

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransactionInterface
     */
    protected $transaction;

    /**
     * @var array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface>
     */
    protected $paymentMethodAdapterCollection;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface
     */
    protected $heidelpayRequestBuilder;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\CaptureTransactionInterface $transaction
     * @param array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface> $paymentMethodAdapterCollection
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface $heidelpayRequestBuilder
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
    public function capture(OrderTransfer $orderTransfer): void
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
    protected function buildCaptureRequest(OrderTransfer $orderTransfer): HeidelpayRequestTransfer
    {
        $captureRequestTransfer = $this->heidelpayRequestBuilder->buildCaptureRequestFromOrder($orderTransfer);

        return $captureRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\CaptureNotSupportedException
     *
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface
     */
    protected function getPaymentMethodAdapter(OrderTransfer $orderTransfer): PaymentWithCaptureInterface
    {
        $paymentMethodCode = $this->getPaymentMethodCode($orderTransfer);

        if (!isset($this->paymentMethodAdapterCollection[$paymentMethodCode])) {
            throw new CaptureNotSupportedException(
                sprintf(static::ERROR_MESSAGE_CAPTURE_TRANSACTION_NOT_SUPPORTED, $paymentMethodCode),
            );
        }

        return $this->paymentMethodAdapterCollection[$paymentMethodCode];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function getPaymentMethodCode(OrderTransfer $orderTransfer): string
    {
        return $orderTransfer->getHeidelpayPayment()->getPaymentMethod();
    }
}
