<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Mapper;

use ArrayObject;

interface EasyCreditResponseToGetParametersMapperInterface
{
    /**
     * @param array<string> $responseAsArray
     * @param \ArrayObject $getParameters
     *
     * @return void
     */
    public function map(array $responseAsArray, ArrayObject $getParameters): void;
}
