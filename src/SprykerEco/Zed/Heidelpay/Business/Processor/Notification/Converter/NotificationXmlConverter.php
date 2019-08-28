<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Converter;

use SimpleXMLElement;
use SprykerEco\Zed\Heidelpay\Business\Exception\TransactionNodeMissingException;

class NotificationXmlConverter implements NotificationXmlConverterInterface
{
    protected const TRANSACTION_ELEMENT = 'Transaction';
    protected const EXCEPTION_MESSAGE_TRANSACTION_NODE_MISSING = 'Notification body has invalid body. Transaction node is missing.';

    /**
     * @param string $xml
     *
     * @return string[][]
     */
    public function convert(string $xml): array
    {
        $xmlElement = $this->loadXml($xml);

        return $this->simpleXmlToArray($xmlElement);
    }

    /**
     * @param string $xml
     *
     * @throws \SprykerEco\Zed\Heidelpay\Business\Exception\TransactionNodeMissingException
     *
     * @return \SimpleXMLElement
     */
    protected function loadXml(string $xml): SimpleXMLElement
    {
        $xmlProcess = new SimpleXMLElement($xml);
        if (!property_exists($xmlProcess, static::TRANSACTION_ELEMENT)) {
            throw new TransactionNodeMissingException(static::EXCEPTION_MESSAGE_TRANSACTION_NODE_MISSING);
        }

        return $xmlProcess->Transaction;
    }

    /**
     * @param \SimpleXMLElement $xmlElement
     *
     * @return string[][]
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
