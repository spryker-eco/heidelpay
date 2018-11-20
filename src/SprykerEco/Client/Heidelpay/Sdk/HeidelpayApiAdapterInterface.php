<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay\Sdk;

interface HeidelpayApiAdapterInterface
{
    /**
     * @param string $messageCode
     * @param string $locale
     *
     * @return string
     */
    public function getTranslatedMessageByCode($messageCode, $locale): string;
}
