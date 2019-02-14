<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Mapper;

interface EasyCreditResponseToGetParametersMapperInterface
{
    /**
     * @param array $responseAsArray
     * @param array $getParameters
     *
     * @return array
     */
    public function getMapped(array $responseAsArray, array $getParameters): array;
}
