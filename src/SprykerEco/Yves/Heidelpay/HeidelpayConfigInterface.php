<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
