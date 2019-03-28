<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Mapper;

use ArrayObject;

class EasyCreditResponseToGetParametersMapper implements EasyCreditResponseToGetParametersMapperInterface
{
    /**
     * @param array $responseAsArray
     * @param \ArrayObject $getParameters
     *
     * @return void
     */
    public function map(array $responseAsArray, ArrayObject $getParameters): void
    {
        $newList = $getParameters->getArrayCopy();
        $newList['IDENTIFICATION_UNIQUEID'] = $responseAsArray['IDENTIFICATION_UNIQUEID'];
        $newList['CRITERION_EASYCREDIT_TOTALAMOUNT'] = $responseAsArray['CRITERION_EASYCREDIT_TOTALAMOUNT'];
        $newList['CRITERION_EASYCREDIT_AMORTISATIONTEXT'] = $responseAsArray['CRITERION_EASYCREDIT_AMORTISATIONTEXT'];
        $newList['CRITERION_EASYCREDIT_ACCRUINGINTEREST'] = $responseAsArray['CRITERION_EASYCREDIT_ACCRUINGINTEREST'];
        $newList['CRITERION_EASYCREDIT_TOTALORDERAMOUNT'] = $responseAsArray['CRITERION_EASYCREDIT_TOTALORDERAMOUNT'];
        $newList['CRITERION_EASYCREDIT_ACCRUINGINTEREST'] = $responseAsArray['CRITERION_EASYCREDIT_ACCRUINGINTEREST'];
        $newList['CRITERION_EASYCREDIT_TOTALAMOUNT'] = $responseAsArray['CRITERION_EASYCREDIT_TOTALAMOUNT'];
        $newList['CRITERION_EASYCREDIT_PRECONTRACTINFORMATIONURL'] = $responseAsArray['CRITERION_EASYCREDIT_PRECONTRACTINFORMATIONURL'];

        $getParameters->exchangeArray($newList);
    }
}
