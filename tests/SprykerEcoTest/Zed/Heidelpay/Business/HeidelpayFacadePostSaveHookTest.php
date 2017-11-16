<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerEco\Zed\Heidelpay\HeidelpayConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulCreditCardSecureTransaction;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulIdealAuthorizeTransaction;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulPaypalAuthorizeTransaction;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulPaypalDebitTransaction;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\OrderWithSuccessfulSofortAuthorizeTransaction;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeCaptureTest
 */
class HeidelpayFacadePostSaveHookTest extends Test
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

        $this->heidelpayFacade = (new HeidelpayFacade())
            ->setFactory(new HeidelpayBusinessFactory());

        $this->getModule('\\' . ConfigHelper::class)
            ->setConfig(HeidelpayConstants::CONFIG_ENCRYPTION_KEY, 'encryption_key');
    }

    /**
     * @dataProvider _createOrderWithSofortAuthorizeTransaction
     * @dataProvider _createOrderWithPaypalDebitTransaction
     * @dataProvider _createOrderWithPaypalAuthorizeTransaction
     * @dataProvider _createOrderWithCreditCardSecureTransaction
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function testPostSaveHookForSuccessfulSalesOrdersSetsExternalRedirect(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $this->heidelpayFacade->postSaveHook(
            $quoteTransfer,
            $checkoutResponseTransfer
        );

        $this->assertTrue($checkoutResponseTransfer->getIsExternalRedirect());
        $this->assertEquals(
            HeidelpayTestConstants::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL,
            $checkoutResponseTransfer->getRedirectUrl()
        );
    }

    /**
     * @dataProvider _createOrderWithIdealAuthorizeTransaction
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function testPostSaveHookForSuccessfulIdealAuthorizeSetsRedirectToTheIdealFormStep(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $this->getModule('\\' . ConfigHelper::class)
            ->setConfig(HeidelpayConstants::CONFIG_YVES_CHECKOUT_IDEAL_AUTHORIZE_URL, '');

        $this->heidelpayFacade->postSaveHook(
            $quoteTransfer,
            $checkoutResponseTransfer
        );

        $idealAuthorizeStepUrl = (new HeidelpayConfig())
            ->getIdealAuthorizeUrl();

        $this->assertTrue($checkoutResponseTransfer->getIsExternalRedirect());
        $this->assertEquals(
            $idealAuthorizeStepUrl,
            $checkoutResponseTransfer->getRedirectUrl()
        );
    }

    /**
     * @return array
     */
    public function _createOrderWithPaypalDebitTransaction()
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulPaypalDebitTransaction();

        return [$orderWithPaypalAuthorize->createOrderWithPaypalDebitTransaction()];
    }

    /**
     * @return array
     */
    public function _createOrderWithIdealAuthorizeTransaction()
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulIdealAuthorizeTransaction();

        return [$orderWithPaypalAuthorize->createOrderWithIdealAuthorizeTransaction()];
    }

    /**
     * @return array
     */
    public function _createOrderWithSofortAuthorizeTransaction()
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulSofortAuthorizeTransaction();

        return [$orderWithPaypalAuthorize->createOrderWithSofortAuthorizeTransaction()];
    }

    /**
     * @return array
     */
    public function _createOrderWithPaypalAuthorizeTransaction()
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulPaypalAuthorizeTransaction();

        return [$orderWithPaypalAuthorize->createOrderWithPaypalAuthorizeTransaction()];
    }

    /**
     * @return array
     */
    public function _createOrderWithCreditCardSecureTransaction()
    {
        $orderWithPaypalAuthorize = new OrderWithSuccessfulCreditCardSecureTransaction();

        return [$orderWithPaypalAuthorize->createOrderWithCreditCardSecureTransaction()];
    }
}
