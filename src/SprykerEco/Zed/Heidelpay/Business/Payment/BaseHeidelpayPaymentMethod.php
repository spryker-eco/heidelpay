<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @return string|null
     */
    protected function getCheckoutRedirectUrlFromAuthorizeTransactionLog($idSalesOrder)
    {
        $authorizeTransactionLogTransfer = $this->findOrderAuthorizeTransactionLog($idSalesOrder);

        if (($authorizeTransactionLogTransfer !== null) &&
            ($this->isAuthorizeTransactionSentSuccessfully($authorizeTransactionLogTransfer))
        ) {
            return $this->getAuthorizeRedirectUrl($authorizeTransactionLogTransfer);
        }

        return $this->getAuthorizeFailedRedirectUrl($authorizeTransactionLogTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string|null
     */
    protected function getCheckoutRedirectUrlFromAuthorizeOnRegistrationTransactionLog($idSalesOrder)
    {
        $authorizeTransactionLogTransfer = $this->findOrderAuthorizeOnRegistrationTransactionLog($idSalesOrder);

        if (($authorizeTransactionLogTransfer !== null) &&
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
    protected function findOrderAuthorizeTransactionLog($idSalesOrder)
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
    protected function setExternalRedirect($redirectUrl, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $checkoutResponseTransfer->setIsExternalRedirect(true);
        $checkoutResponseTransfer->setRedirectUrl($redirectUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $authorizeTransactionLogTransfer
     *
     * @return bool
     */
    protected function isAuthorizeTransactionSentSuccessfully(HeidelpayTransactionLogTransfer $authorizeTransactionLogTransfer)
    {
        return $authorizeTransactionLogTransfer->getHeidelpayResponse()->getIsSuccess();
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $transactionLogTransfer
     *
     * @return string
     */
    protected function getAuthorizeRedirectUrl(HeidelpayTransactionLogTransfer $transactionLogTransfer)
    {
        return $transactionLogTransfer->getHeidelpayResponse()->getPaymentFormUrl();
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $transactionLogTransfer
     *
     * @return string
     */
    protected function getAuthorizeFailedRedirectUrl(HeidelpayTransactionLogTransfer $transactionLogTransfer)
    {
        $errorCode = $transactionLogTransfer->getHeidelpayResponse()->getError()->getCode();
        $paymentFailedUrl = $this->config->getYvesCheckoutPaymentFailedUrl();

        return sprintf($paymentFailedUrl, $errorCode);
    }
}
