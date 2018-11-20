<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay\Dependency\Client;

interface HeidelpayToLocaleInterface
{
    /**
     * @return string
     */
    public function getCurrentLocale(): string;
}
