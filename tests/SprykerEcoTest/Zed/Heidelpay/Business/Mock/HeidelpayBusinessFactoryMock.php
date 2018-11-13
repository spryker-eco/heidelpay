<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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

    /**
     * @return \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface
     */
    public function getHeidelpayQueryContainer(): HeidelpayQueryContainerInterface
    {
        return $this->getQueryContainer();
    }
}
