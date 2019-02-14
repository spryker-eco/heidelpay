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
            'IDENTIFICATION_UNIQUEID' => $responseAsArray['IDENTIFICATION_UNIQUEID'],
            'CRITERION_EASYCREDIT_TOTALAMOUNT' => $responseAsArray['CRITERION_EASYCREDIT_TOTALAMOUNT'],
            'CRITERION_EASYCREDIT_AMORTISATIONTEXT' => $responseAsArray['CRITERION_EASYCREDIT_AMORTISATIONTEXT'],
            'CRITERION_EASYCREDIT_ACCRUINGINTEREST' => $responseAsArray['CRITERION_EASYCREDIT_ACCRUINGINTEREST'],
        ];

        return $result;
    }
}
