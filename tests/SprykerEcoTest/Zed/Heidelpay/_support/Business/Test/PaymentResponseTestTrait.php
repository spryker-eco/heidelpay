<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Test;

use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use SprykerEcoTest\Zed\Heidelpay\Business\HeidelpayTestConfig;

trait PaymentResponseTestTrait
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $responseTransfer
     *
     * @return void
     */
    protected function testSuccessfulIntializeHeidelpayPaymentResponse(HeidelpayResponseTransfer $responseTransfer): void
    {
        $this->assertNotNull($responseTransfer->getIdTransactionUnique());
        $this->assertTrue($responseTransfer->getIsSuccess());
        $this->assertNotNull($responseTransfer->getCustomerRedirectUrl());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $responseTransfer
     *
     * @return void
     */
    protected function testUnsuccessfulIntializeHeidelpayPaymentResponse(HeidelpayResponseTransfer $responseTransfer): void
    {
        $this->assertFalse($responseTransfer->getIsSuccess());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $transaction
     *
     * @return void
     */
    protected function testSuccessfulHeidelpayPaymentResponse(HeidelpayTransactionLogTransfer $transaction): void
    {
        $this->assertNotNull($transaction->getHeidelpayResponse());
        $this->assertInstanceOf(HeidelpayResponseTransfer::class, $transaction->getHeidelpayResponse());
        $this->assertFalse($transaction->getHeidelpayResponse()->getIsError());
        $this->assertTrue($transaction->getHeidelpayResponse()->getIsSuccess());
        $this->assertEquals(
            HeidelpayTestConfig::HEIDELPAY_SUCCESS_RESPONSE,
            $transaction->getHeidelpayResponse()->getResultCode(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer $transaction
     *
     * @return void
     */
    protected function testUnsuccessfulHeidelpayPaymentResponse(HeidelpayTransactionLogTransfer $transaction): void
    {
        $this->assertNotNull($transaction->getHeidelpayResponse());
        $this->assertInstanceOf(HeidelpayResponseTransfer::class, $transaction->getHeidelpayResponse());
        $this->assertTrue($transaction->getHeidelpayResponse()->getIsError());
        $this->assertFalse($transaction->getHeidelpayResponse()->getIsSuccess());
    }
}
