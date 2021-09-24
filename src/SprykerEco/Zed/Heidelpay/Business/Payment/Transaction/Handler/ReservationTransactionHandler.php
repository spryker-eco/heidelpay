<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\ReservationNotSupportedException;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ReservationTransactionInterface;

class ReservationTransactionHandler implements ReservationTransactionHandlerInterface
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE_RESERVATION_TRANSACTION_NOT_SUPPORTED =
        'Attempt to call reservation transaction on payment method \'%s\' ' .
        'that does not support it';

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ReservationTransactionInterface
     */
    protected $transaction;

    /**
     * @var array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithReservationInterface>
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
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ReservationTransactionInterface $transaction
     * @param array $paymentMethodAdapterCollection
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromOrderBuilderInterface $heidelpayRequestBuilder
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface $paymentWriter
     */
    public function __construct(
        ReservationTransactionInterface $transaction,
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
    public function executeReservation(OrderTransfer $orderTransfer)
    {
        $reservationRequestTransfer = $this->buildReservationRequest($orderTransfer);
        $paymentAdapter = $this->getPaymentMethodAdapter($orderTransfer);

        $reservationResponseTransfer = $this->transaction->executeTransaction(
            $reservationRequestTransfer,
            $paymentAdapter
        );

        if ($reservationResponseTransfer->getIdTransactionUnique() === null) {
            return;
        }

        $this->paymentWriter->updateHeidelpayPaymentWithResponse($reservationResponseTransfer);
        $orderTransfer->getHeidelpayPayment()->setIdPaymentReference(
            $reservationResponseTransfer->getIdTransactionUnique()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function buildReservationRequest(OrderTransfer $orderTransfer)
    {
        $reservationRequestTransfer = $this->heidelpayRequestBuilder->buildReservationRequestFromOrder($orderTransfer);

        return $reservationRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\ReservationNotSupportedException
     *
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithReservationInterface
     */
    protected function getPaymentMethodAdapter(OrderTransfer $orderTransfer)
    {
        $paymentMethodCode = $this->getPaymentMethodCode($orderTransfer);

        if (!isset($this->paymentMethodAdapterCollection[$paymentMethodCode])) {
            throw new ReservationNotSupportedException(
                sprintf(static::ERROR_MESSAGE_RESERVATION_TRANSACTION_NOT_SUPPORTED, $paymentMethodCode)
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
