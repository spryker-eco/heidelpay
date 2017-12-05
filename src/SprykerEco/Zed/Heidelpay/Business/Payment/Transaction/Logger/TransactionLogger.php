<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Logger;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog;
use SprykerEco\Zed\Heidelpay\Business\Encrypter\EncrypterInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface;

class TransactionLogger implements TransactionLoggerInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface
     */
    protected $utilEncoding;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Encrypter\EncrypterInterface
     */
    protected $encrypter;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface $utilEncoding
     * @param \SprykerEco\Zed\Heidelpay\Business\Encrypter\EncrypterInterface $encrypter
     */
    public function __construct(HeidelpayToUtilEncodingServiceInterface $utilEncoding, EncrypterInterface $encrypter)
    {
        $this->utilEncoding = $utilEncoding;
        $this->encrypter = $encrypter;
    }

    /**
     * @param string $transactionType
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $requestTransfer
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function logTransaction(
        $transactionType,
        HeidelpayRequestTransfer $requestTransfer,
        HeidelpayResponseTransfer $responseTransfer
    ) {
        $transactionLog = new SpyPaymentHeidelpayTransactionLog();
        $this->addEncryptedRequestResponsePayload($transactionLog, $requestTransfer, $responseTransfer);

        $transactionLog
            ->setFkSalesOrder($responseTransfer->getIdSalesOrder())
            ->setTransactionType($transactionType)
            ->setResponseCode($responseTransfer->getResultCode())
            ->setIdTransactionUnique($responseTransfer->getIdTransactionUnique())
            ->setProcessingCode($responseTransfer->getProcessingCode())
            ->setRedirectUrl($responseTransfer->getPaymentFormUrl())
            ->save();
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog $transactionLog
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $requestTransfer
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function addEncryptedRequestResponsePayload(
        SpyPaymentHeidelpayTransactionLog $transactionLog,
        HeidelpayRequestTransfer $requestTransfer,
        HeidelpayResponseTransfer $responseTransfer
    ) {
        $encryptedRequestPayload = $this->encrypter
            ->encryptData($this->encodeRequestTransfer($requestTransfer));
        $encryptedResponsePayload = $this->encrypter
            ->encryptData($responseTransfer->getPayload());

        $transactionLog
            ->setRequestPayload(base64_encode($encryptedRequestPayload))
            ->setResponsePayload(base64_encode($encryptedResponsePayload));
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $requestTransfer
     *
     * @return string
     */
    protected function encodeRequestTransfer(HeidelpayRequestTransfer $requestTransfer)
    {
        return $this->utilEncoding->encodeJson($requestTransfer->toArray());
    }
}
