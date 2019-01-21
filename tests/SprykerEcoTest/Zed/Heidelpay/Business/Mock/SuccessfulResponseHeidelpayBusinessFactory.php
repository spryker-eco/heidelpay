<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock;

use SprykerEco\Zed\Heidelpay\Business\Adapter\AdapterFactoryInterface;

class SuccessfulResponseHeidelpayBusinessFactory extends HeidelpayBusinessFactoryMock
{
    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\AdapterFactoryInterface
     */
    public function createAdapterFactory(): AdapterFactoryInterface
    {
        return $this->createPositiveResponseAdapterFactory();
    }
}
