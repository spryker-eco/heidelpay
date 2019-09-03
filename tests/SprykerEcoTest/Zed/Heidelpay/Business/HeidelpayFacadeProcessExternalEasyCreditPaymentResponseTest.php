<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\SuccessEasyCreditPaymentExternalResponseBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\UnsuccessEasyCreditPaymentExternalResponseBuilder;

/**
 * @group Functional
 * @group SprykerEcoTest
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
        $response = $this
            ->heidelpayFacade
            ->processExternalPaymentResponse($heidelpayResponse);

        $this->assertInstanceOf(HeidelpayPaymentProcessingResponseTransfer::class, $response);
        $this->assertFalse($response->getIsError());
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
        $this->assertEquals(
            'The response object seems to be empty or it is not a valid heidelpay response!',
            $response->getError()->getInternalMessage()
        );
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
     * @return array
     */
    public function createFailedEasyCreditPaymentExternalResponseThatIsUnsuccessful()
    {
        $orderBuilder = new UnsuccessEasyCreditPaymentExternalResponseBuilder($this->createHeidelpayFactory());
        $heidelpayResponse = $orderBuilder->createHeidelpayResponse(PaymentTransfer::HEIDELPAY_EASY_CREDIT);

        return $heidelpayResponse;
    }
}
