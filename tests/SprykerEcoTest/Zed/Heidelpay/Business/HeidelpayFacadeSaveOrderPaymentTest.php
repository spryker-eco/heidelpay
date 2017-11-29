<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardPaymentTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Heidelpay\Persistence\Base\SpyPaymentHeidelpay;
use Orm\Zed\Heidelpay\Persistence\Base\SpyPaymentHeidelpayOrderItem;
use Orm\Zed\Heidelpay\Persistence\Base\SpyPaymentHeidelpayOrderItemQuery;
use Orm\Zed\Heidelpay\Persistence\Base\SpyPaymentHeidelpayQuery;
use Propel\Runtime\Propel;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order\NewOrderWithOneItemTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulCreditCardSecureTransaction;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulSofortAuthorizeTransaction;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Quote\QuoteMockTrait;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeSaveOrderPaymentTest
 */
class HeidelpayFacadeSaveOrderPaymentTest extends Test
{

    const REGISTRATION_NUMBER = '31HA07BC814CA0300B135019D1515E08';

    use NewOrderWithOneItemTrait;

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
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createHeidelpayFactory()
    {
        return new HeidelpayBusinessFactory();
    }


    /**
     * @dataProvider _createOrderWithCreditCardSecureTransaction
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function testSuccessfulSaveOrderPaymentTest(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    )
    {
        $quoteTransfer = $this->createCreditCardPaymentQuote();

        $this->heidelpayFacade->saveOrderPayment($quoteTransfer, $checkoutResponseTransfer);

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
     * @dataProvider _createOrderWithSofortAuthorizeTransaction
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function testUnsuccessfulSaveOrderPaymentTest(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    )
    {
        $quoteTransfer = $this->createSofortPaymentQuote();

        $this->heidelpayFacade->saveOrderPayment($quoteTransfer, $checkoutResponseTransfer);

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
    protected function createCreditCardPaymentQuote()
    {
        $quote = $this->createQuote(PaymentTransfer::HEIDELPAY_CREDIT_CARD_SECURE);

        return $quote;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createSofortPaymentQuote()
    {
        $quote = $this->createQuote(PaymentTransfer::HEIDELPAY_SOFORT);

        return $quote;
    }

    /**
     * @dataProvider _createOrderWithIdealAuthorizeTransaction
     *
     * @return array
     */
    public function _createOrderWithCreditCardSecureTransaction()
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulCreditCardSecureTransaction($this->createHeidelpayFactory());

        return [$orderWithPaypalAuthorize->createOrderWithCreditCardSecureTransaction()];
    }

    /**
     * @return array
     */
    public function _createOrderWithSofortAuthorizeTransaction()
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulSofortAuthorizeTransaction($this->createHeidelpayFactory());

        return [$orderWithPaypalAuthorize->createOrderWithSofortAuthorizeTransaction()];
    }

    /**
     * @return void
     */
    protected function _after()
    {
        $con = Propel::getConnection();
        $con->commit();
    }

    /**
     * @param $correctMethodName
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuote($correctMethodName)
    {
        $quote = new QuoteTransfer();

        $heidelpayCreditCardPayment = new HeidelpayCreditCardPaymentTransfer();
        $heidelpayCreditCardPayment->setSelectedRegistration(
            (new HeidelpayCreditCardRegistrationTransfer())
                ->setRegistrationNumber(static::REGISTRATION_NUMBER)

        );

        $payment = (new PaymentTransfer())
            ->setPaymentMethod($correctMethodName)
            ->setHeidelpayCreditCardSecure($heidelpayCreditCardPayment);

        $quote->setPayment($payment);

        return $quote;
    }

}