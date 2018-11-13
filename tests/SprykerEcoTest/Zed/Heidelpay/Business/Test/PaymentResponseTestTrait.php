<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\Test;

use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use SprykerEcoTest\Zed\Heidelpay\Business\HeidelpayTestConstants;

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
trait PaymentResponseTestTrait
{
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
            HeidelpayTestConstants::HEIDELPAY_SUCCESS_RESPONSE,
            $transaction->getHeidelpayResponse()->getResultCode()
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
