<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Encrypter;

use SprykerEco\Zed\Heidelpay\HeidelpayConfig;

class AesEncrypter implements EncrypterInterface
{
    const CYPHER_METHOD = 'aes-256-cbc';
    const INIT_VECTOR_SEPARATOR = ':::';

    /**
     * @var \SprykerEco\Zed\Heidelpay\HeidelpayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfig $config
     */
    public function __construct(HeidelpayConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $data
     *
     * @return string
     */
    public function encryptData($data)
    {
        $encryptionKey = $this->config
            ->getEncryptionKey();
        $initVector = $this->getRandomPseudoBytes();

        $encryptedData = openssl_encrypt(
            $data,
            static::CYPHER_METHOD,
            $encryptionKey,
            OPENSSL_RAW_DATA,
            $initVector
        );

        return implode(static::INIT_VECTOR_SEPARATOR, [$encryptedData, base64_encode($initVector)]);
    }

    /**
     * @param string $data
     *
     * @return string
     */
    public function decryptData($data)
    {
        $encryptionKey = $this->config
            ->getEncryptionKey();

        list($encryptedData, $initVector) = explode(static::INIT_VECTOR_SEPARATOR, $data);

        return openssl_decrypt(
            $encryptedData,
            static::CYPHER_METHOD,
            $encryptionKey,
            OPENSSL_RAW_DATA,
            base64_decode($initVector)
        );
    }

    /**
     * @return string
     */
    protected function getRandomPseudoBytes()
    {
        $cipherIvLength = openssl_cipher_iv_length(static::CYPHER_METHOD);

        return openssl_random_pseudo_bytes($cipherIvLength);
    }
}
