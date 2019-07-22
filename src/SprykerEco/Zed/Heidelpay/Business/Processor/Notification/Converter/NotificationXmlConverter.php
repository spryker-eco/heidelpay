<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Converter;

use SimpleXMLElement;
use SprykerEco\Zed\Heidelpay\Business\Exception\TransactionElementMissingException;

class NotificationXmlConverter implements NotificationXmlConverterInterface
{
    protected const TRANSACTION_ELEMENT = 'Transaction';

    /**
     * @param string $xml
     *
     * @return array
     */
    public function convert(string $xml): array
    {
        $xmlElement = $this->loadXml($xml);

        return $this->simpleXmlToArray($xmlElement);
    }

    /**
     * @param string $xml
     *
     * @throws \SprykerEco\Zed\Heidelpay\Business\Exception\TransactionElementMissingException
     *
     * @return \SimpleXMLElement
     */
    protected function loadXml(string $xml): SimpleXMLElement
    {
        $xmlProcess = new SimpleXMLElement($xml);
        if (!property_exists($xmlProcess, static::TRANSACTION_ELEMENT)) {
            throw new TransactionElementMissingException();
        }

        return $xmlProcess->Transaction;
    }

    /**
     * @param \SimpleXMLElement $xmlElement
     *
     * @return string[]
     */
    protected function simpleXmlToArray(SimpleXMLElement $xmlElement): array
    {
        $result = (array)$xmlElement;

        foreach ($result as $name => $value) {
            if ($value instanceof SimpleXMLElement) {
                $result[$name] = $this->simpleXmlToArray($value);
            }
        }

        return $result;
    }
}
