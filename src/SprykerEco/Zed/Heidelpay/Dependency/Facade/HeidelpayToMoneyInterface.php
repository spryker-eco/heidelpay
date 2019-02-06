<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Dependency\Facade;

interface HeidelpayToMoneyInterface
{
    /**
     * @param int $value
     *
     * @return float
     */
    public function convertIntegerToDecimal($value);
}
