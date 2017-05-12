<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger;

use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog;
use SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingInterface;

class TransactionLogger implements TransactionLoggerInterface
{

    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingInterface
     */
    private $utilEncoding;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingInterface $utilEncoding
     */
    public function __construct(HeidelpayToUtilEncodingInterface $utilEncoding)
    {
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param string $transactionType
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer|null $requestTransfer
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function logTransaction(
        $transactionType,
        $requestTransfer,
        HeidelpayResponseTransfer $responseTransfer
    ) {
        $transactionLog = new SpyPaymentHeidelpayTransactionLog();
        $transactionLog
            ->setFkSalesOrder($responseTransfer->getIdSalesOrder())
            ->setTransactionType($transactionType)
            ->setResponseCode($responseTransfer->getResultCode())
            ->setIdTransactionUnique($responseTransfer->getIdTransactionUnique())
            ->setProcessingCode($responseTransfer->getProcessingCode())
            ->setRedirectUrl($responseTransfer->getPaymentFormUrl())
            ->setRequestPayload($this->getRequestPayload($requestTransfer))
            ->setResponsePayload($responseTransfer->getPayload())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer|null $requestTransfer
     *
     * @return string|null
     */
    protected function getRequestPayload($requestTransfer)
    {
        if ($requestTransfer === null) {
            return null;
        }

        return $this->getRequestTransferEncoded($requestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $requestTransfer
     *
     * @return string
     */
    protected function getRequestTransferEncoded($requestTransfer): string
    {
        return $this->utilEncoding->encodeJson($requestTransfer->toArray());
    }

}
