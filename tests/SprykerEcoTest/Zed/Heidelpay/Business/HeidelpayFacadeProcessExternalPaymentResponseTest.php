<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\FailedSofortPaymentExternalResponseWhithFailedProcessingResultBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\FailedSofortPaymentExternalResponseWhithIncorrectHashBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\FailedSofortPaymentExternalResponseWhithIncorrectTransactionIdBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse\SuccessSofortPaymentExternalResponseBuilder;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeProcessExternalPaymentResponseTest
 */
class HeidelpayFacadeProcessExternalPaymentResponseTest extends Test
{

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

        $this->getModule('\\' . ConfigHelper::class)
            ->setConfig(HeidelpayConstants::CONFIG_ENCRYPTION_KEY, 'encryption_key');
        $this->getModule('\\' . ConfigHelper::class)
            ->setConfig(HeidelpayConstants::CONFIG_HEIDELPAY_APPLICATION_SECRET, 'debug_secret');

        $this->heidelpayFacade = (new HeidelpayFacade())
            ->setFactory($this->createHeidelpayFactory());
    }

    /**
     * @return void
     */
    public function testProcessExternalPaymentSuccessSofortPaymentResponse()
    {
        $heidelpayResponse = $this->createSuccessSofortPaymentExternalResponse();

        $response = $this->heidelpayFacade->processExternalPaymentResponse(
            $heidelpayResponse
        );

        $this->assertInstanceOf(HeidelpayPaymentProcessingResponseTransfer::class, $response);
        $this->assertFalse($response->getIsError());
    }

    /**
     * @return array
     */
    public function createSuccessSofortPaymentExternalResponse()
    {
        $orderBuilder = new SuccessSofortPaymentExternalResponseBuilder($this->createHeidelpayFactory());
        $heidelpayResponse = $orderBuilder->createHeidelpayResponse(PaymentTransfer::HEIDELPAY_SOFORT);

        return $heidelpayResponse;
    }

    /**
     * @return void
     */
    public function testProcessExternalPaymentFailedSofortResponseWhichUnsuccessful()
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
    public function createFailedSofortPaymentExternalResponseThatIsUnsuccessful()
    {
        $orderBuilder = new FailedSofortPaymentExternalResponseWhithFailedProcessingResultBuilder($this->createHeidelpayFactory());
        $heidelpayResponse = $orderBuilder->createHeidelpayResponse(PaymentTransfer::HEIDELPAY_SOFORT);

        return $heidelpayResponse;
    }

    /**
     * @return void
     */
    public function testProcessExternalPaymentFailedSofortResponseWhithIncorrectHash()
    {
        $heidelpayResponse = $this->createFailedSofortPaymentExternalResponseWhithIncorrectHash();

        $response = $this->heidelpayFacade->processExternalPaymentResponse(
            $heidelpayResponse
        );

        $this->assertInstanceOf(HeidelpayPaymentProcessingResponseTransfer::class, $response);
        $this->assertTrue($response->getIsError());
        $this->assertEquals('Hash does not match. This could be some kind of manipulation or misconfiguration!', $response->getError()->getInternalMessage());
    }

    /**
     * @return array
     */
    public function createFailedSofortPaymentExternalResponseWhithIncorrectHash()
    {
        $orderBuilder = new FailedSofortPaymentExternalResponseWhithIncorrectHashBuilder($this->createHeidelpayFactory());
        $heidelpayResponse = $orderBuilder->createHeidelpayResponse(PaymentTransfer::HEIDELPAY_SOFORT);

        return $heidelpayResponse;
    }

    /**
     * @return void
     */
    public function testProcessExternalPaymentFailedSofortResponseWithIncorrectHeidelpayTransactionId()
    {
        $heidelpayResponse = $this->createFailedSofortPaymentExternalResponseWhithIncorrectHash();

        $response = $this->heidelpayFacade->processExternalPaymentResponse(
            $heidelpayResponse
        );

        $this->assertInstanceOf(HeidelpayPaymentProcessingResponseTransfer::class, $response);
        $this->assertTrue($response->getIsError());
        $this->assertEquals('Hash does not match. This could be some kind of manipulation or misconfiguration!', $response->getError()->getInternalMessage());
    }

    /**
     * @return array
     */
    public function createFailedSofortPaymentExternalResponseWithIncorrectHeidelpayTransactionId()
    {
        $orderBuilder = new FailedSofortPaymentExternalResponseWhithIncorrectTransactionIdBuilder($this->createHeidelpayFactory());
        $heidelpayResponse = $orderBuilder->createHeidelpayResponse(PaymentTransfer::HEIDELPAY_SOFORT);

        return $heidelpayResponse;
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createHeidelpayFactory()
    {
        return new HeidelpayBusinessFactory();
    }

}
