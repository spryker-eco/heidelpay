<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Encrypter;

interface EncrypterInterface
{
    /**
     * @param string $data
     *
     * @return string
     */
    public function encryptData(string $data): string;

    /**
     * @param string $data
     *
     * @return string|false|null
     */
    public function decryptData(string $data);
}
