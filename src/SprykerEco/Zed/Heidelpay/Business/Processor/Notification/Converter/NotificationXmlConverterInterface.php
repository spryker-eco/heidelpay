<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Converter;

interface NotificationXmlConverterInterface
{
    /**
     * @param string $xml
     *
     * @return array<array<mixed>>
     */
    public function convert(string $xml): array;
}
