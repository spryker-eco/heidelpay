<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\FailedSofortPaymentExternalResponseWhithFailedProcessingResultBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\FailedSofortPaymentExternalResponseWhithIncorrectHashBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\FailedSofortPaymentExternalResponseWhithIncorrectTransactionIdBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\SuccessSofortPaymentExternalResponseBuilder;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeProcessExternalPaymentResponseTest
 */
class HeidelpayFacadeProcessExternalPaymentResponseTest extends HeidelpayPaymentTest
{
    /**
     * @return void
     */
    public function testProcessExternalPaymentSuccessSofortPaymentResponse(): void
    {
        $heidelpayResponse = $this->createSuccessSofortPaymentExternalResponse();

        $response = $this->heidelpayFacade->processExternalPaymentResponse(
            $heidelpayResponse
        );

        $this->assertInstanceOf(HeidelpayPaymentProcessingResponseTransfer::class, $response);
        $this->assertFalse($response->getIsError());
    }

    /**
     * @return void
     */
    public function testProcessExternalPaymentFailedSofortResponseWhichUnsuccessful(): void
    {
        $heidelpayResponse = $this->createFailedSofortPaymentExternalResponseThatIsUnsuccessful();

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
    public function createSuccessSofortPaymentExternalResponse(): array
    {
        $orderBuilder = new SuccessSofortPaymentExternalResponseBuilder($this->createHeidelpayFactory());
        $heidelpayResponse = $orderBuilder->createHeidelpayResponse(PaymentTransfer::HEIDELPAY_SOFORT);

        return $heidelpayResponse;
    }

    /**
     * @return array
     */
    public function createFailedSofortPaymentExternalResponseThatIsUnsuccessful(): array
    {
        $orderBuilder = new FailedSofortPaymentExternalResponseWhithFailedProcessingResultBuilder($this->createHeidelpayFactory());
        $heidelpayResponse = $orderBuilder->createHeidelpayResponse(PaymentTransfer::HEIDELPAY_SOFORT);

        return $heidelpayResponse;
    }
}
