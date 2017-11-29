<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Codeception\TestCase\Test;
use Propel\Runtime\Propel;
use Spryker\Zed\Sales\Business\SalesFacade;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesBridge;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\PaymentHeidelpayTransferBuilderTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\Test\PaymentResponseTestTrait;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

class HeidelpayPaymentTest extends Test
{
    use PaymentHeidelpayTransferBuilderTrait, PaymentResponseTestTrait;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade
     */
    protected $heidelpayFacade;

    /**
     * @var
     */
    protected $heidelpayFactory;

    /**
     * @var
     */
    protected $heidelpayToSales;

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();

        $this->heidelpayToSales = new HeidelpayToSalesBridge(new SalesFacade());

        $this->getModule('\\' . ConfigHelper::class)
            ->setConfig(HeidelpayConstants::CONFIG_ENCRYPTION_KEY, 'encryption_key');
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createHeidelpayFactory()
    {
        return new HeidelpayBusinessFactory();
    }

    /**
     * @return void
     */
    protected function _after()
    {
        $con = Propel::getConnection();
        $con->commit();
    }

}