<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Heidelpay;

use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class HeidelpayConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getApplicationSecret()
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_APPLICATION_SECRET);
    }

}
