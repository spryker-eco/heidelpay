<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay;

interface HeidelpayConfigInterface
{
    /**
     * @return string
     */
    public function getYvesCheckoutPaymentStepPath(): string;

    /**
     * @return string
     */
    public function getYvesCheckoutPaymentFailedUrl(): string;

    /**
     * @return string
     */
    public function getYvesCheckoutSummaryStepUrl(): string;

    /**
     * @return string
     */
    public function getYvesCheckoutSuccessUrl(): string;

    /**
     * @return string
     */
    public function getYvesRegistrationSuccessUrl(): string;
}
