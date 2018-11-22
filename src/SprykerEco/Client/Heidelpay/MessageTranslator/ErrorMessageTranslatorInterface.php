<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay\MessageTranslator;

/**
 * @method \SprykerEco\Client\Heidelpay\HeidelpayFactory getFactory()
 */
interface ErrorMessageTranslatorInterface
{
    /**
     * @param string $errorCode
     * @param string $locale
     *
     * @return string
     */
    public function getTranslatedErrorMessageByCode($errorCode, $locale): string;
}
