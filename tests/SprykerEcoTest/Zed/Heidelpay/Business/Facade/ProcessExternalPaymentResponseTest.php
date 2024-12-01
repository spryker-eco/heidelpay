<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\FailedSofortPaymentExternalResponseWhithFailedProcessingResultBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\SuccessSofortPaymentExternalResponseBuilder;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group Facade
 * @group ProcessExternalPaymentResponseTest
 */
class ProcessExternalPaymentResponseTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testProcessExternalPaymentSuccessSofortPaymentResponse(): void
    {
        // Arrange
        $heidelpayResponse = $this->createSuccessSofortPaymentExternalResponse();

        // Act
        $response = $this->heidelpayFacade->processExternalPaymentResponse($heidelpayResponse);

        // Assert
        $this->assertInstanceOf(HeidelpayPaymentProcessingResponseTransfer::class, $response);
        $this->assertFalse($response->getIsError());
    }

    /**
     * @return void
     */
    public function testProcessExternalPaymentFailedSofortResponseWhichUnsuccessful(): void
    {
        // Arrange
        $heidelpayResponse = $this->createFailedSofortPaymentExternalResponseThatIsUnsuccessful();

        // Act
        $response = $this->heidelpayFacade->processExternalPaymentResponse($heidelpayResponse);

        // Assert
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
    protected function createSuccessSofortPaymentExternalResponse(): array
    {
        $orderBuilder = new SuccessSofortPaymentExternalResponseBuilder($this->createHeidelpayFactory());

        return $orderBuilder->createHeidelpayResponse(PaymentTransfer::HEIDELPAY_SOFORT);
    }

    /**
     * @return array
     */
    protected function createFailedSofortPaymentExternalResponseThatIsUnsuccessful(): array
    {
        $orderBuilder = new FailedSofortPaymentExternalResponseWhithFailedProcessingResultBuilder($this->createHeidelpayFactory());

        return $orderBuilder->createHeidelpayResponse(PaymentTransfer::HEIDELPAY_SOFORT);
    }
}
