<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Codeception\TestCase\Test;
use Propel\Runtime\Propel;
use Spryker\Zed\Sales\Business\SalesFacade;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
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
    protected function _before(): void
    {
        parent::_before();

        $this->heidelpayToSales = new HeidelpayToSalesBridge(new SalesFacade());

        $this->heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createHeidelpayFactory());

        $config = $this->getConfigOptions();

        foreach ($config as $key => $value) {
            $this->getModule('\\' . ConfigHelper::class)
                ->setConfig($key, $value);
        }
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createHeidelpayFactory(): HeidelpayBusinessFactory
    {
        return new HeidelpayBusinessFactory();
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        $con = Propel::getConnection();
        $con->commit();
    }

    /**
     * @return array
     */
    protected function getConfigOptions(): array
    {
        return (new HeidelpayConfigurationBuilder())->getHeidelpayConfigurationOptions();
    }

    /**
     * @param string $dataProviderFunctionName
     * @param string $testFunctionName
     *
     * @return void
     */
    protected function testExecutor(string $dataProviderFunctionName, string $testFunctionName): void
    {
        $data = $this->$dataProviderFunctionName();
        list($quoteTransfer, $checkoutResponseTransfer) = $data;
        $this->$testFunctionName($quoteTransfer, $checkoutResponseTransfer);
    }
}
