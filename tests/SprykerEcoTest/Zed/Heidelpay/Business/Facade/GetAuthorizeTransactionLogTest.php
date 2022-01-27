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
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulIdealAuthorizeTransaction;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithUnsuccessfulIdealAuthorizeTransaction;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group Facade
 * @group GetAuthorizeTransactionLogTest
 */
class GetAuthorizeTransactionLogTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testSuccessfulGetAuthorizeTransactionLog(): void
    {
        //Arrange
        $quoteTransfer = $this->createOrderWithSuccessfulIdealAuthorizeTransaction();
        $authorizeTransactionLogRequestTransfer = new HeidelpayAuthorizeTransactionLogRequestTransfer();
        $authorizeTransactionLogRequestTransfer->setOrderReference($quoteTransfer->getOrderReference());

        //Act
        $transactionLogTransfer = $this->heidelpayFacade->getAuthorizeTransactionLog($authorizeTransactionLogRequestTransfer);

        //Assert
        $this->assertInstanceOf(HeidelpayTransactionLogTransfer::class, $transactionLogTransfer);
        $heidelpayResponse = $transactionLogTransfer->getHeidelpayResponse();

        $this->assertInstanceOf(HeidelpayResponseTransfer::class, $heidelpayResponse);
        $this->assertEquals(
            $quoteTransfer->getPayment()->getHeidelpayIdeal()->getFkSalesOrder(),
            $heidelpayResponse->getIdSalesOrder(),
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
        //Arrange
        $quoteTransfer = $this->createOrderWithUnsuccessfulIdealAuthorizeTransaction();
        $authorizeTransactionLogRequestTransfer = new HeidelpayAuthorizeTransactionLogRequestTransfer();
        $authorizeTransactionLogRequestTransfer->setOrderReference($quoteTransfer->getOrderReference());

        //Act
        $transactionLogTransfer = $this->heidelpayFacade->getAuthorizeTransactionLog($authorizeTransactionLogRequestTransfer);

        //Assert
        $this->assertInstanceOf(HeidelpayTransactionLogTransfer::class, $transactionLogTransfer);

        $heidelpayResponse = $transactionLogTransfer->getHeidelpayResponse();
        $this->assertInstanceOf(HeidelpayResponseTransfer::class, $heidelpayResponse);
        $this->assertEquals(HeidelpayTestConfig::HEIDELPAY_UNSUCCESS_RESPONSE, $heidelpayResponse->getResultCode());
        $this->assertEquals(
            $quoteTransfer->getPayment()->getHeidelpayIdeal()->getFkSalesOrder(),
            $heidelpayResponse->getIdSalesOrder(),
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
    protected function createOrderWithSuccessfulIdealAuthorizeTransaction(): QuoteTransfer
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulIdealAuthorizeTransaction($this->createHeidelpayFactory());
        $order = $orderWithPaypalAuthorize->createOrderWithIdealAuthorizeTransaction();

        return $order[0];
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createOrderWithUnsuccessfulIdealAuthorizeTransaction(): QuoteTransfer
    {
        $orderWithPaypalAuthorize = new OrderWithUnsuccessfulIdealAuthorizeTransaction($this->createHeidelpayFactory());
        $order = $orderWithPaypalAuthorize->createOrderWithIdealAuthorizeTransaction();

        return $order[0];
    }
}
