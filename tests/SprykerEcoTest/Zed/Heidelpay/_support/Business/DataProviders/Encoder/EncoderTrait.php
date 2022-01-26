<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Encoder;

use SprykerEco\Zed\Heidelpay\Business\Encrypter\AesEncrypter;

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
trait EncoderTrait
{
    /**
     * @param string $data
     *
     * @return string
     */
    public function encryptData(string $data): string
    {
        $config = $this->factory->getConfig();
        $enc = (new AesEncrypter($config))->encryptData($data);
        $enc = base64_encode($enc);

        return $enc;
    }
}
