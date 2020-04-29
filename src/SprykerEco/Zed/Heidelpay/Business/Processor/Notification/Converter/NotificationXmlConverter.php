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
        $result = [];

        /**
         * When this $xmlElement has children, then add its attributes. If not, don't add the attributes.
         * This is a quick-fix & workaround for a fixed PHP bug (#61597) to preserve backwards-compatibility for users
         * of this module. In a future version we should change this to always return all attributes, and then mark
         * it as a backwards-incompatible release to let our users change their code to expect an array and not a string
         * in the places where a node only contains text (and not children).
         */
        $attributes = $xmlElement->attributes();
        if ($xmlElement->count() > 0 && $attributes->count() > 0) {
            $result['@attributes'] = ((array)$attributes)['@attributes'];
        }

        foreach ($xmlElement->children() as $node) {
            /* @var \SimpleXMLElement $node */

            $result[$node->getName()] = (
                ($node->count() > 0)
                ? $this->simpleXmlToArray($node)
                : (string)$node
            );
        }

        return $result;
    }
}
