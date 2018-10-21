<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
