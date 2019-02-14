<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Mapper;

class EasyCreditResponseToGetParametersMapper implements EasyCreditResponseToGetParametersMapperInterface
{
    /**
     * @param array $responseAsArray
     * @param array $getParameters
     *
     * @return array
     */
    public function getMapped(array $responseAsArray, array $getParameters): array
    {
        $result = [
            'IDENTIFICATION.UNIQUEID' => $responseAsArray['IDENTIFICATION.UNIQUEID'],
            'CRITERION.EASYCREDIT_TOTALAMOUNT' => $responseAsArray['CRITERION.EASYCREDIT_TOTALAMOUNT'],
            'CRITERION.EASYCREDIT_AMORTISATIONTEXT' => $responseAsArray['CRITERION.EASYCREDIT_AMORTISATIONTEXT'],
            'CRITERION.EASYCREDIT_ACCRUINGINTEREST' => $responseAsArray['CRITERION.EASYCREDIT_ACCRUINGINTEREST'],
        ];

        return $result;
    }
}
