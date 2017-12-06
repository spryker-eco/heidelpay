<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;

class HeidelpayConfig extends AbstractBundleConfig implements HeidelpayConfigInterface
{
    /**
     * @return string
     */
    public function getYvesCheckoutPaymentStepPath()
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_STEP_PATH);
    }

    /**
     * @return string
     */
    public function getYvesCheckoutPaymentFailedUrl()
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FAILED_URL);
    }

    /**
     * @return string
     */
    public function getYvesCheckoutSummaryStepUrl()
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_SUMMARY_STEP_URL);
    }

    /**
     * @return string
     */
    public function getYvesCheckoutSuccessUrl()
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_SUCCESS_URL);
    }

    /**
     * @return string
     */
    public function getYvesRegistrationSuccessUrl()
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_REGISTRATION_SUCCESS_URL);
    }
}
