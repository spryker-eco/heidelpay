<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardPaymentTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Heidelpay\Persistence\Base\SpyPaymentHeidelpayOrderItemQuery;
use Orm\Zed\Heidelpay\Persistence\Base\SpyPaymentHeidelpayQuery;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order\NewOrderWithOneItemTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulCreditCardSecureTransaction;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulSofortAuthorizeTransaction;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeSaveOrderPaymentTest
 */
class HeidelpayFacadeSaveOrderPaymentTest extends HeidelpayPaymentTest
{
    use NewOrderWithOneItemTrait;

    /**
     * @var string
     */
    protected const REGISTRATION_NUMBER = '31HA07BC814CA0300B135019D1515E08';

    /**
     * @var string
     */
    protected const REGISTRATION_NUMBER = '31HA07BC814CA0300B135019D1515E08';

    /**
     * @dataProvider functionListForSuccessfulSaveOrderPaymentTest
     *
     * @param string $dataProviderFunctionName
     * @param string $testFunctionName
     *
     * @return void
     */
    public function testSuccessfulSaveOrderPaymentTest(string $dataProviderFunctionName, string $testFunctionName): void
    {
        $this->testExecutor($dataProviderFunctionName, $testFunctionName);
    }

    /**
     * @return array
     */
    public static function functionListForSuccessfulSaveOrderPaymentTest(): array
    {
        return [
            ['createOrderWithCreditCardSecureTransaction', 'successfulSaveOrderPaymentTest'],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function successfulSaveOrderPaymentTest(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void {
        $quoteTransfer = $this->createCreditCardPaymentQuote();

        $this->heidelpayFacade->saveOrderPayment($quoteTransfer, $checkoutResponseTransfer->getSaveOrder());

        $heidelpayOrderItems = $checkoutResponseTransfer
            ->getSaveOrder()
            ->getOrderItems();

        $heidelpayOrderItemQuery = SpyPaymentHeidelpayOrderItemQuery::create();
        $heidelpayPaymentQuery = SpyPaymentHeidelpayQuery::create();
        foreach ($heidelpayOrderItems->getArrayCopy() as $item) {
            $this->assertNotNull($item->getIdSalesOrderItem());
            $savedResult = $heidelpayOrderItemQuery->findByFkSalesOrderItem($item->getIdSalesOrderItem());

            $this->assertNotNull($savedResult);
            $this->assertInstanceOf('Propel\Runtime\Collection\ObjectCollection', $savedResult);
            $this->assertGreaterThan(0, $savedResult->count());
            $savedOrderItem = $savedResult->getData()[0];
            $this->assertInstanceOf('Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayOrderItem', $savedOrderItem);
            $this->assertEquals($item->getIdSalesOrderItem(), $savedOrderItem->getFkSalesOrderItem());

            $savedPaymentResult = $heidelpayPaymentQuery->findByIdPaymentHeidelpay($savedOrderItem->getFkPaymentHeidelpay());
            $this->assertNotNull($savedPaymentResult);
            $this->assertInstanceOf('Propel\Runtime\Collection\ObjectCollection', $savedPaymentResult);
            $this->assertGreaterThan(0, $savedPaymentResult->count());
            $savedPayment = $savedPaymentResult->getData()[0];
            $this->assertInstanceOf('Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay', $savedPayment);

            $this->assertNotNull($savedPayment->getIdPaymentRegistration());
            $this->assertEquals(static::REGISTRATION_NUMBER, $savedPayment->getIdPaymentRegistration());
            $this->assertEquals(PaymentTransfer::HEIDELPAY_CREDIT_CARD_SECURE, $savedPayment->getPaymentMethod());
        }
    }

    /**
     * @dataProvider functionListForUnsuccessfulSaveOrderPaymentTest
     *
     * @param string $dataProviderFunctionName
     * @param string $testFunctionName
     *
     * @return void
     */
    public function testUnsuccessfulSaveOrderPaymentTest($dataProviderFunctionName, $testFunctionName): void
    {
        $this->testExecutor($dataProviderFunctionName, $testFunctionName);
    }

    /**
     * @return array
     */
    public static function functionListForUnsuccessfulSaveOrderPaymentTest(): array
    {
        return [
            ['createOrderWithCreditCardSecureTransaction', 'unsuccessfulSaveOrderPaymentTest'],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function unsuccessfulSaveOrderPaymentTest(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void {
        $quoteTransfer = $this->createSofortPaymentQuote();

        $this->heidelpayFacade->saveOrderPayment($quoteTransfer, $checkoutResponseTransfer->getSaveOrder());

        $heidelpayOrderItems = $checkoutResponseTransfer
            ->getSaveOrder()
            ->getOrderItems();

        $heidelpayOrderItemQuery = SpyPaymentHeidelpayOrderItemQuery::create();
        $heidelpayPaymentQuery = SpyPaymentHeidelpayQuery::create();
        foreach ($heidelpayOrderItems->getArrayCopy() as $item) {
            $this->assertNotNull($item->getIdSalesOrderItem());
            $savedItemResult = $heidelpayOrderItemQuery->findByFkSalesOrderItem($item->getIdSalesOrderItem());

            $this->assertNotNull($savedItemResult);
            $this->assertInstanceOf('Propel\Runtime\Collection\ObjectCollection', $savedItemResult);
            $this->assertGreaterThan(0, $savedItemResult->count());
            $savedOrderItem = $savedItemResult->getData()[0];
            $this->assertInstanceOf('Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayOrderItem', $savedOrderItem);
            $this->assertEquals($item->getIdSalesOrderItem(), $savedOrderItem->getFkSalesOrderItem());

            $savedPaymentResult = $heidelpayPaymentQuery->findByIdPaymentHeidelpay($savedOrderItem->getFkPaymentHeidelpay());
            $this->assertNotNull($savedPaymentResult);
            $this->assertInstanceOf('Propel\Runtime\Collection\ObjectCollection', $savedPaymentResult);
            $this->assertGreaterThan(0, $savedPaymentResult->count());
            $savedPayment = $savedPaymentResult->getData()[0];
            $this->assertInstanceOf('Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay', $savedPayment);

            $this->assertNull($savedPayment->getIdPaymentRegistration());
            $this->assertEquals(PaymentTransfer::HEIDELPAY_SOFORT, $savedPayment->getPaymentMethod());
        }
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createCreditCardPaymentQuote(): QuoteTransfer
    {
        $quote = $this->createQuote(PaymentTransfer::HEIDELPAY_CREDIT_CARD_SECURE);

        return $quote;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createSofortPaymentQuote(): QuoteTransfer
    {
        $quote = $this->createQuote(PaymentTransfer::HEIDELPAY_SOFORT);

        return $quote;
    }

    /**
     * @return array
     */
    public function createOrderWithCreditCardSecureTransaction(): array
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulCreditCardSecureTransaction($this->createHeidelpayFactory());

        return $orderWithPaypalAuthorize->createOrderWithCreditCardSecureTransaction();
    }

    /**
     * @return array
     */
    public function createOrderWithSofortAuthorizeTransaction(): array
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulSofortAuthorizeTransaction($this->createHeidelpayFactory());

        return $orderWithPaypalAuthorize->createOrderWithSofortAuthorizeTransaction();
    }

    /**
     * @param string $correctMethodName
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuote(string $correctMethodName): QuoteTransfer
    {
        $quote = new QuoteTransfer();

        $heidelpayCreditCardPayment = new HeidelpayCreditCardPaymentTransfer();
        $heidelpayCreditCardPayment->setSelectedRegistration(
            (new HeidelpayCreditCardRegistrationTransfer())
                ->setRegistrationNumber(static::REGISTRATION_NUMBER),
        );

        $payment = (new PaymentTransfer())
            ->setPaymentMethod($correctMethodName)
            ->setHeidelpayCreditCardSecure($heidelpayCreditCardPayment);

        $quote->setTotals(
            (new TotalsTransfer())
                ->setNetTotal(10000)
                ->setTaxTotal((new TaxTotalTransfer())->setAmount(10))
                ->setDiscountTotal(0),
        );

        $quote->setCurrency((new CurrencyTransfer())->setCode('EUR'));

        $quote->setPayment($payment);

        return $quote;
    }
}
