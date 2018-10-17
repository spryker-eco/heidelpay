<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Heidelpay\MessageTranslator;

use SprykerEco\Client\Heidelpay\Sdk\HeidelpayApiAdapterInterface;

/**
 * @method \SprykerEco\Client\Heidelpay\HeidelpayFactory getFactory()
 */
class ErrorMessageTranslator implements ErrorMessageTranslatorInterface
{
    /**
     * @var \SprykerEco\Client\Heidelpay\Sdk\HeidelpayApiAdapterInterface
     */
    protected $heidelpayApiAdapter;

    /**
     * @param \SprykerEco\Client\Heidelpay\Sdk\HeidelpayApiAdapterInterface $heidelpayApiAdapter
     */
    public function __construct(HeidelpayApiAdapterInterface $heidelpayApiAdapter)
    {
        $this->heidelpayApiAdapter = $heidelpayApiAdapter;
    }

    /**
     * @param string $errorCode
     * @param string $locale
     *
     * @return string
     */
    public function getTranslatedErrorMessageByCode($errorCode, $locale)
    {
        $translatedMessage = $this->getSdkTranslation($errorCode, $locale);

        return $translatedMessage;
    }

    /**
     * @param string $errorCode
     * @param string $locale
     *
     * @return string
     */
    protected function getSdkTranslation($errorCode, $locale)
    {
        return $this->heidelpayApiAdapter
            ->getTranslatedMessageByCode($errorCode, $locale);
    }
}
