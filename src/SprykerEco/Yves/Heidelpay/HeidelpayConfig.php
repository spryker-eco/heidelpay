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
     * @return string
     */
    public function getYvesCheckoutPaymentStepPath(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_STEP_PATH);
    }

    /**
     * @return string
     */
    public function getYvesCheckoutPaymentFailedUrl(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_PAYMENT_FAILED_URL);
    }

    /**
     * @return string
     */
    public function getYvesCheckoutSummaryStepUrl(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_SUMMARY_STEP_URL);
    }

    /**
     * @return string
     */
    public function getYvesCheckoutSuccessUrl(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_SUCCESS_URL);
    }

    /**
     * @return string
     */
    public function getYvesRegistrationSuccessUrl(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_YVES_CHECKOUT_REGISTRATION_SUCCESS_URL);
    }
}
