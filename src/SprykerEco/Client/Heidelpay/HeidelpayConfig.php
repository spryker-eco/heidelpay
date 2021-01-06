<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;

class HeidelpayConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getApplicationSecret(): string
    {
        return $this->get(HeidelpayConstants::CONFIG_HEIDELPAY_APPLICATION_SECRET);
    }
}
