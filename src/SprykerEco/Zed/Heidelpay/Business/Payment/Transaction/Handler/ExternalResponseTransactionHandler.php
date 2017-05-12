<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Spryker\Zed\Heidelpay\Business\Payment\PaymentWriterInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilderInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\Exception\ExternalResponseNotSupportedException;
use Spryker\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransactionInterface;

class ExternalResponseTransactionHandler implements ExternalResponseTransactionHandlerInterface
{

    const ERROR_MESSAGE_EXTERNAL_RESPONSE_TRANSACTION_NOT_SUPPORTED =
        'Attempt to call external response transaction on payment method \'%s\' ' .
        'that does not support it';

    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransactionInterface
     */
    protected $transaction;

    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface[]
     */
    protected $paymentMethodAdapterCollection;

    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\PaymentReaderInterface
     */
    protected $paymentWriter;

    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilderInterface
     */
    protected $externalPaymentResponseBuilder;

    /**
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransactionInterface $transaction
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface[] $paymentMethodAdapterCollection
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Request\ExternalPaymentResponseBuilderInterface $externalPaymentResponseBuilder
     * @param \Spryker\Zed\Heidelpay\Business\Payment\PaymentWriterInterface $paymentWriter
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
    public function processExternalPaymentResponse(array $externalResponseArray)
    {
        $externalResponseTransfer = $this->buildExternalResponseTransfer($externalResponseArray);
        $transactionResultTransfer = $this->executeTransaction($externalResponseTransfer);
        $this->updateIdPaymentReference($transactionResultTransfer);

        return $this->buildPaymentProcessingResponse($transactionResultTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function updateIdPaymentReference(HeidelpayResponseTransfer $responseTransfer)
    {
        $this->paymentWriter->updatePaymentReferenceByIdSalesOrder(
            $responseTransfer->getIdPaymentReference(),
            $responseTransfer->getIdSalesOrder()
        );
    }

    /**
     * @param array $externalResponseArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer
     */
    protected function buildExternalResponseTransfer(array $externalResponseArray)
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
    protected function executeTransaction(HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer)
    {
        $paymentAdapter = $this->getPaymentMethodAdapter($externalResponseTransfer);
        $responseTransfer = $this->transaction->executeTransaction($externalResponseTransfer, $paymentAdapter);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer
     *
     * @throws \Spryker\Zed\Heidelpay\Business\Payment\Transaction\Exception\ExternalResponseNotSupportedException
     *
     * @return \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface
     */
    protected function getPaymentMethodAdapter(HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer)
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
    protected function buildPaymentProcessingResponse(HeidelpayResponseTransfer $transactionResultTransfer)
    {
        $paymentProcessingResponseTransfer = (new HeidelpayPaymentProcessingResponseTransfer())
            ->setIsError(false);

        if ($transactionResultTransfer->getIsError()) {
            $paymentProcessingResponseTransfer
                ->setIsError(true)
                ->setError($transactionResultTransfer->getError());
        }

        return $paymentProcessingResponseTransfer;
    }

}
