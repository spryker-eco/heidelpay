<?php

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock;

use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
class HeidelpayBusinessFactoryMock extends HeidelpayBusinessFactory
{

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\AdapterFactoryInterface
     */
    public function createPositiveResponseAdapterFactory()
    {
        return new SuccessfulResponseAdapterFactory();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\AdapterFactoryInterface
     */
    public function createUnsuccessfulResponseAdapterFactory()
    {
        return new UnsuccessfulResponseAdapterFactory();
    }
}