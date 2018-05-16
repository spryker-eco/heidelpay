<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Heidelpay\Sdk;

use Heidelpay\MessageCodeMapper\MessageCodeMapper;

/**
 * @method \SprykerEco\Client\Heidelpay\HeidelpayFactory getFactory()
 */
class HeidelpayApiAdapter implements HeidelpayApiAdapterInterface
{
    /**
     * @param string $messageCode
     * @param string $locale
     *
     * @return string
     */
    public function getTranslatedMessageByCode($messageCode, $locale)
    {
        $heidelpayMessage = new MessageCodeMapper($locale);

        return $heidelpayMessage->getMessage($messageCode);
    }
}
