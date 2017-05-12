<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
