<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayAuthorizeTransactionLogRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseErrorTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulIdealAuthorizeTransaction;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithUnsuccessfulIdealAuthorizeTransaction;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeGetAuthorizeTransactionLogTest
 */
class HeidelpayFacadeGetAuthorizeTransactionLogTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testSuccessfulGetAuthorizeTransactionLog(): void
    {
        $quoteTransfer = $this->createOrderWithSuccessfulIdealAuthorizeTransaction();
        $authorizeTransactionLogRequestTransfer = new HeidelpayAuthorizeTransactionLogRequestTransfer();
        $authorizeTransactionLogRequestTransfer->setOrderReference($quoteTransfer->getOrderReference());

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createHeidelpayFactory());

        $transactionLogTransfer = $heidelpayFacade->getAuthorizeTransactionLog($authorizeTransactionLogRequestTransfer);

        $this->assertInstanceOf(HeidelpayTransactionLogTransfer::class, $transactionLogTransfer);

        $this->assertNotNull($transactionLogTransfer->getHeidelpayResponse());
        $heidelpayResponse = $transactionLogTransfer->getHeidelpayResponse();

        $this->assertInstanceOf(HeidelpayResponseTransfer::class, $heidelpayResponse);
        $this->assertEquals(
            $quoteTransfer->getPayment()->getHeidelpayIdeal()->getFkSalesOrder(),
            $heidelpayResponse->getIdSalesOrder()
        );

        $this->assertNotEmpty($heidelpayResponse->getPayload());

        $this->assertTrue($heidelpayResponse->getIsSuccess());
        $this->assertFalse($heidelpayResponse->getIsError());
        $this->assertEquals(HeidelpayTestConfig::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL, $heidelpayResponse->getPaymentFormUrl());
    }

    /**
     * @return void
     */
    public function testUnsuccessfulGetAuthorizeTransactionLog(): void
    {
        $quoteTransfer = $this->createOrderWithUnsuccessfulIdealAuthorizeTransaction();
        $authorizeTransactionLogRequestTransfer = new HeidelpayAuthorizeTransactionLogRequestTransfer();
        $authorizeTransactionLogRequestTransfer->setOrderReference($quoteTransfer->getOrderReference());

        $heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createHeidelpayFactory());

        $transactionLogTransfer = $heidelpayFacade->getAuthorizeTransactionLog($authorizeTransactionLogRequestTransfer);

        $this->assertInstanceOf(HeidelpayTransactionLogTransfer::class, $transactionLogTransfer);

        $this->assertNotNull($transactionLogTransfer->getHeidelpayResponse());
        $heidelpayResponse = $transactionLogTransfer->getHeidelpayResponse();

        $this->assertInstanceOf(HeidelpayResponseTransfer::class, $heidelpayResponse);
        $this->assertEquals(HeidelpayTestConfig::HEIDELPAY_UNSUCCESS_RESPONSE, $heidelpayResponse->getResultCode());
        $this->assertEquals(
            $quoteTransfer->getPayment()->getHeidelpayIdeal()->getFkSalesOrder(),
            $heidelpayResponse->getIdSalesOrder()
        );

        $this->assertNotEmpty($heidelpayResponse->getPayload());

        $this->assertInstanceOf(HeidelpayResponseErrorTransfer::class, $heidelpayResponse->getError());
        $this->assertNotEmpty($heidelpayResponse->getError()->getInternalMessage());

        $this->assertFalse($heidelpayResponse->getIsSuccess());
        $this->assertTrue($heidelpayResponse->getIsError());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createOrderWithSuccessfulIdealAuthorizeTransaction(): QuoteTransfer
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulIdealAuthorizeTransaction($this->createHeidelpayFactory());
        $order = $orderWithPaypalAuthorize->createOrderWithIdealAuthorizeTransaction();

        return $order[0];
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createOrderWithUnsuccessfulIdealAuthorizeTransaction(): QuoteTransfer
    {
        $orderWithPaypalAuthorize = new OrderWithUnsuccessfulIdealAuthorizeTransaction($this->createHeidelpayFactory());
        $order = $orderWithPaypalAuthorize->createOrderWithIdealAuthorizeTransaction();

        return $order[0];
    }
}
