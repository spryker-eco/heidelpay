<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\FailedEasyCreditPaymentExternalResponseWhithFailedProcessingResultBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\FailedEasyCreditPaymentExternalResponseWhithIncorrectHashBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\FailedEasyCreditPaymentExternalResponseWhithIncorrectTransactionIdBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\SuccessEasyCreditPaymentExternalResponseBuilder;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeProcessExternalEasyCreditPaymentResponseTest
 */
class HeidelpayFacadeProcessExternalEasyCreditPaymentResponseTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testProcessExternalPaymentSuccessEasyCreditPaymentResponse()
    {
        $heidelpayResponse = $this->createSuccessEasyCreditPaymentExternalResponse();

        $response = $this->heidelpayFacade->processExternalPaymentResponse(
            $heidelpayResponse
        );

        $this->assertInstanceOf(HeidelpayPaymentProcessingResponseTransfer::class, $response);
        $this->assertFalse($response->getIsError());
    }

    /**
     * @return array
     */
    public function createSuccessEasyCreditPaymentExternalResponse()
    {
        $orderBuilder = new SuccessEasyCreditPaymentExternalResponseBuilder($this->createHeidelpayFactory());
        $heidelpayResponse = $orderBuilder->createHeidelpayResponse(PaymentTransfer::HEIDELPAY_EASY_CREDIT);

        return $heidelpayResponse;
    }

    /**
     * @return void
     */
    public function testProcessExternalPaymentFailedEasyCreditResponseWhichUnsuccessful()
    {
        $heidelpayResponse = $this->createFailedEasyCreditPaymentExternalResponseThatIsUnsuccessful();

        $response = $this->heidelpayFacade->processExternalPaymentResponse(
            $heidelpayResponse
        );

        $this->assertInstanceOf(HeidelpayPaymentProcessingResponseTransfer::class, $response);
        $this->assertTrue($response->getIsError());
        $this->assertEquals('The response object seems to be empty or it is not a valid heidelpay response!', $response->getError()->getInternalMessage());
    }

    /**
     * @return array
     */
    public function createFailedEasyCreditPaymentExternalResponseThatIsUnsuccessful()
    {
        $orderBuilder = new FailedEasyCreditPaymentExternalResponseWhithFailedProcessingResultBuilder($this->createHeidelpayFactory());
        $heidelpayResponse = $orderBuilder->createHeidelpayResponse(PaymentTransfer::HEIDELPAY_EASY_CREDIT);

        return $heidelpayResponse;
    }

    /**
     * @return void
     */
    public function testProcessExternalPaymentFailedxEasyCreditResponseWhithIncorrectHash()
    {
        $heidelpayResponse = $this->createFailedEasyCreditPaymentExternalResponseWhithIncorrectHash();

        $response = $this->heidelpayFacade->processExternalPaymentResponse(
            $heidelpayResponse
        );

        $this->assertInstanceOf(HeidelpayPaymentProcessingResponseTransfer::class, $response);
        $this->assertTrue($response->getIsError());
        $this->assertEquals('Hashes do not match. This could be some kind of manipulation or misconfiguration!', $response->getError()->getInternalMessage());
    }

    /**
     * @return array
     */
    public function createFailedEasyCreditPaymentExternalResponseWhithIncorrectHash()
    {
        $orderBuilder = new FailedEasyCreditPaymentExternalResponseWhithIncorrectHashBuilder($this->createHeidelpayFactory());
        $heidelpayResponse = $orderBuilder->createHeidelpayResponse(PaymentTransfer::HEIDELPAY_EASY_CREDIT);

        return $heidelpayResponse;
    }

    /**
     * @return void
     */
    public function testProcessExternalPaymentFailedEasyCreditResponseWithIncorrectHeidelpayTransactionId()
    {
        $heidelpayResponse = $this->createFailedEasyCreditPaymentExternalResponseWhithIncorrectHash();

        $response = $this->heidelpayFacade->processExternalPaymentResponse(
            $heidelpayResponse
        );

        $this->assertInstanceOf(HeidelpayPaymentProcessingResponseTransfer::class, $response);
        $this->assertTrue($response->getIsError());
        $this->assertEquals('Hashes do not match. This could be some kind of manipulation or misconfiguration!', $response->getError()->getInternalMessage());
    }

    /**
     * @return array
     */
    public function createFailedEasyCreditPaymentExternalResponseWithIncorrectHeidelpayTransactionId()
    {
        $orderBuilder = new FailedEasyCreditPaymentExternalResponseWhithIncorrectTransactionIdBuilder($this->createHeidelpayFactory());
        $heidelpayResponse = $orderBuilder->createHeidelpayResponse(PaymentTransfer::HEIDELPAY_EASY_CREDIT);

        return $heidelpayResponse;
    }
}
