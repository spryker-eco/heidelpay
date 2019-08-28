<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\ExternalResponseNotSupportedException;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransactionInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface;

class ExternalResponseTransactionHandler implements ExternalResponseTransactionHandlerInterface
{
    public const ERROR_MESSAGE_EXTERNAL_RESPONSE_TRANSACTION_NOT_SUPPORTED =
        'Attempt to call external response transaction on payment method \'%s\' ' .
        'that does not support it';

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransactionInterface
     */
    protected $transaction;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface[]
     */
    protected $paymentMethodAdapterCollection;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface
     */
    protected $paymentWriter;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilderInterface
     */
    protected $externalPaymentResponseBuilder;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransactionInterface $transaction
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface[] $paymentMethodAdapterCollection
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilderInterface $externalPaymentResponseBuilder
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface $paymentWriter
     */
    public function __construct(
        ExternalResponseTransactionInterface $transaction,
        array $paymentMethodAdapterCollection,
        ExternalPaymentResponseBuilderInterface $externalPaymentResponseBuilder,
        PaymentWriterInterface $paymentWriter
    ) {
        $this->transaction = $transaction;
        $this->paymentMethodAdapterCollection = $paymentMethodAdapterCollection;
        $this->externalPaymentResponseBuilder = $externalPaymentResponseBuilder;
        $this->paymentWriter = $paymentWriter;
    }

    /**
     * @param array $externalResponseArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalPaymentResponse(array $externalResponseArray): HeidelpayPaymentProcessingResponseTransfer
    {
        $externalResponseTransfer = $this->buildExternalResponseTransfer($externalResponseArray);
        $transactionResultTransfer = $this->executeTransaction($externalResponseTransfer);
        $this->updatePaymentHeidelpayWithExternalResponse($transactionResultTransfer);

        return $this->buildPaymentProcessingResponse($transactionResultTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function updatePaymentHeidelpayWithExternalResponse(HeidelpayResponseTransfer $responseTransfer): void
    {
        if ($responseTransfer->getIdPaymentReference() === null
            && $responseTransfer->getIdTransactionUnique() === null
        ) {
            return;
        }

        $this->paymentWriter->updateHeidelpayPaymentWithResponse($responseTransfer);
    }

    /**
     * @param array $externalResponseArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer
     */
    protected function buildExternalResponseTransfer(array $externalResponseArray): HeidelpayExternalPaymentResponseTransfer
    {
        $externalResponseTransfer = $this->externalPaymentResponseBuilder
            ->buildExternalResponseTransfer($externalResponseArray);

        return $externalResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    protected function executeTransaction(HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer): HeidelpayResponseTransfer
    {
        $paymentAdapter = $this->getPaymentMethodAdapter($externalResponseTransfer);
        $responseTransfer = $this->transaction->executeTransaction($externalResponseTransfer, $paymentAdapter);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer
     *
     * @throws \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\ExternalResponseNotSupportedException
     *
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface
     */
    protected function getPaymentMethodAdapter(HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer): PaymentWithExternalResponseInterface
    {
        $paymentMethodCode = $externalResponseTransfer->getPaymentMethod();

        if (!isset($this->paymentMethodAdapterCollection[$paymentMethodCode])) {
            throw new ExternalResponseNotSupportedException(
                sprintf(static::ERROR_MESSAGE_EXTERNAL_RESPONSE_TRANSACTION_NOT_SUPPORTED, $paymentMethodCode)
            );
        }

        return $this->paymentMethodAdapterCollection[$paymentMethodCode];
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $transactionResultTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    protected function buildPaymentProcessingResponse(HeidelpayResponseTransfer $transactionResultTransfer): HeidelpayPaymentProcessingResponseTransfer
    {
        return (new HeidelpayPaymentProcessingResponseTransfer())
            ->fromArray(
                $transactionResultTransfer->toArray(),
                true
            );
    }
}
