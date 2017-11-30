<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Codeception\TestCase\Test;

use Generated\Shared\Transfer\HeidelpayPaymentTransfer;

use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\PaymentBuilder;

use SprykerTest\Shared\Testify\Helper\ConfigHelper;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeGetPaymentBySalesOrderTest
 */
class HeidelpayFacadeGetPaymentBySalesOrderTest extends Test
{

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade
     */
    protected $heidelpayFacade;

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();

        $this->heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createHeidelpayFactory());

        $this->getModule('\\' . ConfigHelper::class)
            ->setConfig(HeidelpayConstants::CONFIG_ENCRYPTION_KEY, 'encryption_key');
    }

    /**
     * @dataProvider _createSofortSuccessOrder
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrder
     *
     * @return void
     */
    public function testProcessExternalPaymentResponseForSofort(SpySalesOrder $salesOrder)
    {
        $paymentTransfer = $this->heidelpayFacade->getPaymentByIdSalesOrder($salesOrder->getIdSalesOrder());
        $this->assertInstanceOf(HeidelpayPaymentTransfer::class, $paymentTransfer);
        $this->assertNotNull($paymentTransfer);
        $this->assertEquals(HeidelpayTestConstants::HEIDELPAY_REFERENCE, $paymentTransfer->getIdPaymentReference());
        $this->assertEquals(PaymentTransfer::HEIDELPAY_SOFORT, $paymentTransfer->getPaymentMethod());
        $this->assertNotNull($paymentTransfer->getFkSalesOrder());
    }

    /**
     * @return array
     */
    public function _createSofortSuccessOrder()
    {
        $orderBuilder = new PaymentBuilder($this->createHeidelpayFactory());
        $orderTransfer = $orderBuilder->createPayment(PaymentTransfer::HEIDELPAY_SOFORT);
        return [$orderTransfer];
    }

    /**
     * @return void
     */
    public function testProcessExternalPaymentResponseForSofortForFailedPayment()
    {
        $paymentTransfer = $this->heidelpayFacade->getPaymentByIdSalesOrder(1000000000);
        $this->assertInstanceOf(HeidelpayPaymentTransfer::class, $paymentTransfer);
        $this->assertNotNull($paymentTransfer);
        $paymentTransferArray = $paymentTransfer->toArray();
        foreach ($paymentTransferArray as $paymentTransferOption => $value) {
            $this->assertNull($value);
        }
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createHeidelpayFactory()
    {
        return new HeidelpayBusinessFactory();
    }

}