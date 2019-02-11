<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Codeception\TestCase\Test;
use Propel\Runtime\Propel;
use Spryker\Zed\Sales\Business\SalesFacade;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeBridge;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Payment\PaymentHeidelpayTransferBuilderTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\Test\PaymentResponseTestTrait;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

class HeidelpayPaymentTest extends Test
{
    use PaymentHeidelpayTransferBuilderTrait, PaymentResponseTestTrait;

    /**
     * @var \SprykerEcoTest\Zed\Heidelpay\HeidelpayZedTester
     */
    protected $tester;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade
     */
    protected $heidelpayFacade;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected $heidelpayFactory;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToSalesFacadeBridge
     */
    protected $heidelpayToSales;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->heidelpayToSales = new HeidelpayToSalesFacadeBridge(new SalesFacade());

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
        [$quoteTransfer, $checkoutResponseTransfer] = $data;
        $this->$testFunctionName($quoteTransfer, $checkoutResponseTransfer);
    }
}
