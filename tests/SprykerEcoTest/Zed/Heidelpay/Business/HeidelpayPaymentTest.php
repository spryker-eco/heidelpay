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
     * @var \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected $heidelpayFactory;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesBridge
     */
    protected $heidelpayToSales;

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();

        $this->heidelpayToSales = new HeidelpayToSalesBridge(new SalesFacade());

        $config[HeidelpayConstants::CONFIG_ENCRYPTION_KEY] = 'encryption_key';
        $config[HeidelpayConstants::CONFIG_HEIDELPAY_USER_LOGIN] = '31ha07bc8142c5a171744e5aef11ffd3';

        foreach ($config as $key => $value) {
            $this->getModule('\\' . ConfigHelper::class)
                ->setConfig($key, $value);
        }
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
