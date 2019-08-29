<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Mock;

use SprykerEco\Zed\Heidelpay\Business\Adapter\AdapterFactoryInterface;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface;

class HeidelpayBusinessFactoryMock extends HeidelpayBusinessFactory
{
    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\AdapterFactoryInterface
     */
    public function createPositiveResponseAdapterFactory(): AdapterFactoryInterface
    {
        return new SuccessfulResponseAdapterFactory();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\AdapterFactoryInterface
     */
    public function createUnsuccessfulResponseAdapterFactory(): AdapterFactoryInterface
    {
        return new UnsuccessfulResponseAdapterFactory();
    }
}
