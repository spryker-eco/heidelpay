<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
    public function getTranslatedMessageByCode($messageCode, $locale): string
    {
        $heidelpayMessage = new MessageCodeMapper($locale);

        return $heidelpayMessage->getMessage($messageCode);
    }
}
