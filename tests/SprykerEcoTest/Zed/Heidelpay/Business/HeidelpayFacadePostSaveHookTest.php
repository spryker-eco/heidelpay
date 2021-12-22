<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Zed\Heidelpay\HeidelpayConfig;
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulCreditCardSecureTransaction;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulIdealAuthorizeTransaction;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulPaypalAuthorizeTransaction;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulPaypalDebitTransaction;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulSofortAuthorizeTransaction;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadePostSaveHookTest
 */
class HeidelpayFacadePostSaveHookTest extends HeidelpayPaymentTest
{
    /**
     * @dataProvider functionForPostSaveHookForSuccessfulSalesOrdersSetsExternalRedirectTest
     *
     * @param string $dataProviderFunctionName
     * @param string $testFunctionName
     *
     * @return void
     */
    public function testPostSaveHookForSuccessfulSalesOrdersSetsExternalRedirect(string $dataProviderFunctionName, string $testFunctionName): void
    {
        $this->testExecutor($dataProviderFunctionName, $testFunctionName);
    }

    /**
     * @return array
     */
    public static function functionForPostSaveHookForSuccessfulSalesOrdersSetsExternalRedirectTest(): array
    {
        return [
            ['createOrderWithSofortAuthorizeTransaction', 'postSaveHookForSuccessfulSalesOrdersSetsExternalRedirectTest'],
            ['createOrderWithPaypalDebitTransaction', 'postSaveHookForSuccessfulSalesOrdersSetsExternalRedirectTest'],
            ['createOrderWithPaypalAuthorizeTransaction', 'postSaveHookForSuccessfulSalesOrdersSetsExternalRedirectTest'],
            ['createOrderWithCreditCardSecureTransaction', 'postSaveHookForSuccessfulSalesOrdersSetsExternalRedirectTest'],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function postSaveHookForSuccessfulSalesOrdersSetsExternalRedirectTest(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void {
        $this->heidelpayFacade->postSaveHook(
            $quoteTransfer,
            $checkoutResponseTransfer,
        );

        $this->assertTrue($checkoutResponseTransfer->getIsExternalRedirect());
        $this->assertEquals(
            HeidelpayTestConfig::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL,
            $checkoutResponseTransfer->getRedirectUrl(),
        );
    }

    /**
     * @dataProvider functionForPostSaveHookForSuccessfulSalesOrdersSetsExternalRedirectTest
     *
     * @param string $dataProviderFunctionName
     * @param string $testFunctionName
     *
     * @return void
     */
    public function testPostSaveHookForSuccessfulIdealAuthorizeSetsRedirectToTheIdealFormStepTest($dataProviderFunctionName, $testFunctionName): void
    {
        $this->testExecutor($dataProviderFunctionName, $testFunctionName);
    }

    /**
     * @return array
     */
    public static function functionForPostSaveHookForSuccessfulIdealAuthorizeSetsRedirectToTheIdealFormStepTest(): array
    {
        return [
            ['createOrderWithCreditCardSecureTransaction', 'postSaveHookForSuccessfulIdealAuthorizeSetsRedirectToTheIdealFormStepTest'],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function postSaveHookForSuccessfulIdealAuthorizeSetsRedirectToTheIdealFormStepTest(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void {
        $this->getModule('\\' . ConfigHelper::class)
            ->setConfig(HeidelpayConstants::CONFIG_YVES_CHECKOUT_IDEAL_AUTHORIZE_URL, '');

        $this->heidelpayFacade->postSaveHook(
            $quoteTransfer,
            $checkoutResponseTransfer,
        );

        $idealAuthorizeStepUrl = (new HeidelpayConfig())
            ->getIdealAuthorizeUrl();

        $this->assertTrue($checkoutResponseTransfer->getIsExternalRedirect());
        $this->assertEquals(
            $idealAuthorizeStepUrl,
            $checkoutResponseTransfer->getRedirectUrl(),
        );
    }

    /**
     * @return array
     */
    public function createOrderWithPaypalDebitTransaction(): array
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulPaypalDebitTransaction($this->createHeidelpayFactory());

        return $orderWithPaypalAuthorize->createOrderWithPaypalDebitTransaction();
    }

    /**
     * @return array
     */
    public function createOrderWithIdealAuthorizeTransaction(): array
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulIdealAuthorizeTransaction($this->createHeidelpayFactory());

        return $orderWithPaypalAuthorize->createOrderWithIdealAuthorizeTransaction();
    }

    /**
     * @return array
     */
    public function createOrderWithSofortAuthorizeTransaction(): array
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulSofortAuthorizeTransaction($this->createHeidelpayFactory());

        return $orderWithPaypalAuthorize->createOrderWithSofortAuthorizeTransaction();
    }

    /**
     * @return array
     */
    public function createOrderWithPaypalAuthorizeTransaction(): array
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulPaypalAuthorizeTransaction($this->createHeidelpayFactory());

        return $orderWithPaypalAuthorize->createOrderWithPaypalAuthorizeTransaction();
    }

    /**
     * @dataProvider createOrderWithIdealAuthorizeTransaction
     *
     * @return array
     */
    public function createOrderWithCreditCardSecureTransaction(): array
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulCreditCardSecureTransaction($this->createHeidelpayFactory());

        return $orderWithPaypalAuthorize->createOrderWithCreditCardSecureTransaction();
    }
}
