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
 * @group Facade
 * @group ProcessExternalEasyCreditPaymentResponseTest
 */
class ProcessExternalEasyCreditPaymentResponseTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testProcessExternalPaymentSuccessEasyCreditPaymentResponse(): void
    {
        //Arrange
        $heidelpayResponse = $this->createSuccessEasyCreditPaymentExternalResponse();

        //Act
        $response = $this->heidelpayFacade->processExternalPaymentResponse($heidelpayResponse);

        //Assert
        $this->assertInstanceOf(HeidelpayPaymentProcessingResponseTransfer::class, $response);
        $this->assertFalse($response->getIsError());
    }

    /**
     * @return void
     */
    public function testProcessExternalPaymentFailedEasyCreditResponseWhichUnsuccessful(): void
    {
        //Arrange
        $heidelpayResponse = $this->createFailedEasyCreditPaymentExternalResponseThatIsUnsuccessful();

        //Act
        $response = $this->heidelpayFacade->processExternalPaymentResponse($heidelpayResponse);

        //Assert
        $this->assertInstanceOf(HeidelpayPaymentProcessingResponseTransfer::class, $response);
        $this->assertTrue($response->getIsError());
        $this->assertEquals(
            'The response object seems to be empty or it is not a valid heidelpay response!',
            $response->getError()->getInternalMessage(),
        );
    }

    /**
     * @return array
     */
    protected function createSuccessEasyCreditPaymentExternalResponse(): array
    {
        $orderBuilder = new SuccessEasyCreditPaymentExternalResponseBuilder($this->createHeidelpayFactory());

        return $orderBuilder->createHeidelpayResponse(PaymentTransfer::HEIDELPAY_EASY_CREDIT);
    }

    /**
     * @return array
     */
    protected function createFailedEasyCreditPaymentExternalResponseThatIsUnsuccessful(): array
    {
        $orderBuilder = new UnsuccessEasyCreditPaymentExternalResponseBuilder($this->createHeidelpayFactory());

        return $orderBuilder->createHeidelpayResponse(PaymentTransfer::HEIDELPAY_EASY_CREDIT);
    }
}
