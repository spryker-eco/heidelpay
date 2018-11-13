<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @return string
     */
    public function decryptData(string $data);
}
