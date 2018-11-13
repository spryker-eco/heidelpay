<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock;

use SprykerEco\Zed\Heidelpay\Business\Adapter\AdapterFactoryInterface;

class UnsuccesfulResponseHeidelpayBusinessFactory extends HeidelpayBusinessFactoryMock
{
    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\AdapterFactoryInterface
     */
    public function createAdapterFactory(): AdapterFactoryInterface
    {
        return $this->createUnsuccessfulResponseAdapterFactory();
    }
}
