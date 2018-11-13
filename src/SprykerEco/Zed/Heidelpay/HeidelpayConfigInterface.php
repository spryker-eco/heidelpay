<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay;

interface HeidelpayConfigInterface
{
    /**
     * @return string
     */
    public function getMerchantSecuritySender(): string;

    /**
     * @return string
     */
    public function getMerchantUserLogin(): string;

    /**
     * @return string
     */
    public function getMerchantUserPassword(): string;

    /**
     * @return bool
     */
    public function getMerchantSandboxMode(): bool;

    /**
     * @return string
     */
    public function getApplicationSecret(): string;

    /**
     * @return string
     */
    public function getAsyncLanguageCode(): string;

    /**
     * @return string
     */
    public function getZedResponseUrl(): string;

    /**
     * @return string
     */
    public function getYvesCheckoutPaymentFailedUrl(): string;

    /**
     * @return string
     */
    public function getYvesUrlForAsyncIframeResponse(): string;

    /**
     * @return string
     */
    public function getIdealAuthorizeUrl(): string;

    /**
     * @return string
     */
    public function getCreditCardPaymentFrameOrigin(): string;

    /**
     * @return string
     */
    public function getCreditCardPaymentFrameCustomCssUrl(): string;

    /**
     * @return string
     */
    public function getCreditCardPaymentFramePreventAsyncRedirect(): string;

    /**
     * @return bool
     */
    public function getIsSplitPaymentEnabledKey(): bool;

    /**
     * @param string $paymentType
     *
     * @return string
     */
    public function getMerchantTransactionChannelByPaymentType($paymentType): string;
}
