<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;

class HeidelpayConfig extends AbstractBundleConfig implements HeidelpayConfigInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getYvesCheckoutPaymentStepPath(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_STEP_PATH);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getYvesCheckoutPaymentFailedUrl(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FAILED_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getYvesCheckoutSummaryStepUrl(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_SUMMARY_STEP_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getYvesInitializePaymentUrl(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_EASYCREDIT_INITIALIZE_PAYMENT_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getYvesCheckoutSuccessUrl(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_SUCCESS_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getYvesRegistrationSuccessUrl(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_REGISTRATION_SUCCESS_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getEasyCreditLogoUrl(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_EASY_CREDIT_LOGO_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getEasyCreditInfoLink(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_EASY_CREDIT_INFO_LINK);
    }
}
