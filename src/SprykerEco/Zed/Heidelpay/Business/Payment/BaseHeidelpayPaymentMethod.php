<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReaderInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayConfig;

class BaseHeidelpayPaymentMethod
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\HeidelpayConfig
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReaderInterface
     */
    protected $transactionLogManager;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\TransactionLogReaderInterface $transactionLogManager
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfig $config
     */
    public function __construct(
        TransactionLogReaderInterface $transactionLogManager,
        HeidelpayConfig $config
    ) {
        $this->transactionLogManager = $transactionLogManager;
        $this->config = $config;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string
     */
    protected function getCheckoutRedirectUrlFromAuthorizeTransactionLog(int $idSalesOrder): string
    {
        $authorizeTransactionLogTransfer = $this->findOrderAuthorizeTransactionLog($idSalesOrder);

        if (
            ($authorizeTransactionLogTransfer !== null) &&
            ($this->isAuthorizeTransactionSentSuccessfully($authorizeTransactionLogTransfer))
        ) {
            return $this->getAuthorizeRedirectUrl($authorizeTransactionLogTransfer);
        }

        return $this->getAuthorizeFailedRedirectUrl($authorizeTransactionLogTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer|null
     */
    protected function findOrderAuthorizeTransactionLog(int $idSalesOrder): ?HeidelpayTransactionLogTransfer
    {
        return $this->transactionLogManager->findOrderAuthorizeTransactionLogByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer|null
     */
    protected function findOrderAuthorizeOnRegistrationTransactionLog($idSalesOrder)
    {
        return $this->transactionLogManager->findOrderAuthorizeOnRegistrationTransactionLogByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param string $redirectUrl
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function setExternalRedirect(string $redirectUrl, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        $checkoutResponseTransfer->setIsExternalRedirect(true);
        $checkoutResponseTransfer->setRedirectUrl($redirectUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $authorizeTransactionLogTransfer
     *
     * @return bool
     */
    protected function isAuthorizeTransactionSentSuccessfully(HeidelpayTransactionLogTransfer $authorizeTransactionLogTransfer): bool
    {
        return $authorizeTransactionLogTransfer->getHeidelpayResponse()->getIsSuccess();
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $transactionLogTransfer
     *
     * @return string
     */
    protected function getAuthorizeRedirectUrl(HeidelpayTransactionLogTransfer $transactionLogTransfer): string
    {
        return $transactionLogTransfer->getHeidelpayResponse()->getPaymentFormUrl();
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer|null $transactionLogTransfer
     *
     * @return string
     */
    protected function getAuthorizeFailedRedirectUrl(?HeidelpayTransactionLogTransfer $transactionLogTransfer = null): string
    {
        $paymentFailedUrl = $this->config->getYvesCheckoutPaymentFailedUrl();

        if ($transactionLogTransfer === null) {
            return $paymentFailedUrl;
        }

        $errorCode = $transactionLogTransfer->getHeidelpayResponse()->getError()->getCode();

        return sprintf($paymentFailedUrl, $errorCode);
    }
}
