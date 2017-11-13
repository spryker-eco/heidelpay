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
    public function getMerchantSecuritySender();

    /**
     * @return string
     */
    public function getMerchantUserLogin();

    /**
     * @return string
     */
    public function getMerchantUserPassword();

    /**
     * @return bool
     */
    public function getMerchantSandboxMode();

    /**
     * @return string
     */
    public function getApplicationSecret();

    /**
     * @return string
     */
    public function getAsyncLanguageCode();

    /**
     * @return string
     */
    public function getZedResponseUrl();

    /**
     * @return string
     */
    public function getYvesCheckoutPaymentFailedUrl();

    /**
     * @return string
     */
    public function getYvesUrlForAsyncIframeResponse();

    /**
     * @return string
     */
    public function getIdealAuthorizeUrl();

    /**
     * @return string
     */
    public function getCreditCardPaymentFrameOrigin();

    /**
     * @return string
     */
    public function getCreditCardPaymentFrameCustomCssUrl();

    /**
     * @return string
     */
    public function getCreditCardPaymentFramePreventAsyncRedirect();

    /**
     * @param string $paymentType
     *
     * @return string
     */
    public function getMerchantTransactionChannelByPaymentType($paymentType);
}
