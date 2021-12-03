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
    /**
     * @var string
     */
    protected const TRANSACTION_ELEMENT = 'Transaction';
    /**
     * @var string
     */
    protected const EXCEPTION_MESSAGE_TRANSACTION_NODE_MISSING = 'Notification body has invalid body. Transaction node is missing.';

    /**
     * @param string $xml
     *
     * @return array<string[]>
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
     * @return array<string[]>
     */
    protected function simpleXmlToArray(SimpleXMLElement $xmlElement): array
    {
        $result = [];

        $attributes = $xmlElement->attributes();
        if ($attributes !== null && $xmlElement->count() > 0 && $attributes->count() > 0) {
            $result['@attributes'] = ((array)$attributes)['@attributes'];
        }

        foreach ($xmlElement->children() as $node) {
            /** @var \SimpleXMLElement $node */
            $result[$node->getName()] = $node->count() > 0 ? $this->simpleXmlToArray($node) : (string)$node;
        }

        return $result;
    }
}
