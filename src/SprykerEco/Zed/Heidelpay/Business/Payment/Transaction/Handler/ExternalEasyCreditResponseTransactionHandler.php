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
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalEasyCreditPaymentResponseBuilderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransactionInterface;

class ExternalEasyCreditResponseTransactionHandler implements ExternalEasyCreditResponseTransactionHandlerInterface
{
    protected const CRITERION_EASYCREDIT_AMORTISATIONTEXT = 'CRITERION_EASYCREDIT_AMORTISATIONTEXT';
    protected const CRITERION_EASYCREDIT_ACCRUINGINTEREST = 'CRITERION_EASYCREDIT_ACCRUINGINTEREST';
    protected const CRITERION_EASYCREDIT_TOTALAMOUNT = 'CRITERION_EASYCREDIT_TOTALAMOUNT';
    protected const HEIDELPAY_EASY_CREDIT_PAYMENT_METHOD = 'heidelpayEasyCredit';

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
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalEasyCreditPaymentResponseBuilderInterface
     */
    protected $externalEasyCreditPaymentResponseBuilder;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\ExternalResponseTransactionInterface $transaction
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface[] $paymentMethodAdapterCollection
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Request\ExternalEasyCreditPaymentResponseBuilderInterface $externalEasyCreditPaymentResponseBuilder
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentWriterInterface $paymentWriter
     */
    public function __construct(
        ExternalResponseTransactionInterface $transaction,
        array $paymentMethodAdapterCollection,
        ExternalEasyCreditPaymentResponseBuilderInterface $externalEasyCreditPaymentResponseBuilder,
        PaymentWriterInterface $paymentWriter
    ) {
        $this->transaction = $transaction;
        $this->paymentMethodAdapterCollection = $paymentMethodAdapterCollection;
        $this->externalEasyCreditPaymentResponseBuilder = $externalEasyCreditPaymentResponseBuilder;
        $this->paymentWriter = $paymentWriter;
    }

    /**
     * @param array $externalResponseArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalEasyCreditPaymentResponse(array $externalResponseArray)
    {
        $externalResponseTransfer = $this->buildExternalResponseTransfer($externalResponseArray);
        $transactionResultTransfer = $this->executeTransaction($externalResponseTransfer);

        return $this->buildPaymentProcessingResponse($transactionResultTransfer, $externalResponseTransfer);
    }

    /**
     * @param array $externalResponseArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer
     */
    protected function buildExternalResponseTransfer(array $externalResponseArray)
    {
        $externalResponseTransfer = $this->externalEasyCreditPaymentResponseBuilder
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
        $responseTransfer = $this->transaction->executeTransaction($externalResponseTransfer, $this->paymentMethodAdapterCollection[self::HEIDELPAY_EASY_CREDIT_PAYMENT_METHOD]);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $transactionResultTransfer
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    protected function buildPaymentProcessingResponse(
        HeidelpayResponseTransfer $transactionResultTransfer,
        HeidelpayExternalPaymentResponseTransfer $externalResponseTransfer
    ) {
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
