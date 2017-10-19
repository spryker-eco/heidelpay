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
    public function getYvesCheckoutPaymentStepPath();

    /**
     * @return string
     */
    public function getYvesCheckoutPaymentFailedUrl();

    /**
     * @return string
     */
    public function getYvesCheckoutSummaryStepUrl();

    /**
     * @return string
     */
    public function getYvesCheckoutSuccessUrl();

    /**
     * @return string
     */
    public function getYvesRegistrationSuccessUrl();

}
