<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayDirectDebitAccountTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistration;
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\DirectDebit\DirectDebitRegistrationBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order\OrderAddressTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Quote\QuoteMockTrait;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeRetrieveDirectDebitRegistrationTest
 */
class HeidelpayFacadeRetrieveDirectDebitRegistrationTest extends HeidelpayPaymentTest
{
    use OrderAddressTrait;
    use QuoteMockTrait;

    /**
     * @return void
     */
    public function testSuccessfulRetrieveDirectDebitRegistration(): void
    {
        //Arrange
        $quoteTransfer = $this->createQuote();
        $directDebitRegistrationTransfer = $this->createHeidelpayDirectDebitRegistrationTransfer();
        $directDebitRegistrationEntity = $this->createDirectDebitRegistration($quoteTransfer);
        $directDebitRegistrationTransfer
            ->setIdDirectDebitRegistration(
                $directDebitRegistrationEntity->getIdDirectDebitRegistration(),
            );

        //Act
        $directDebitRegistrationTransfer = $this->heidelpayFacade->retrieveDirectDebitRegistration($directDebitRegistrationTransfer);

        //Assert
        $this->assertInstanceOf(HeidelpayDirectDebitRegistrationTransfer::class, $directDebitRegistrationTransfer);
        $this->assertNotNull($directDebitRegistrationTransfer->getIdDirectDebitRegistration());
        $this->assertNotNull($directDebitRegistrationTransfer->getRegistrationUniqueId());
        $this->assertInstanceOf(HeidelpayDirectDebitAccountTransfer::class, $directDebitRegistrationTransfer->getAccountInfo());
        $this->assertNotNull($directDebitRegistrationTransfer->getAccountInfo()->getAccountIban());
        $this->assertNotNull($directDebitRegistrationTransfer->getAccountInfo()->getAccountBic());
        $this->assertNotNull($directDebitRegistrationTransfer->getAccountInfo()->getAccountHolder());
    }

    /**
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    protected function createHeidelpayDirectDebitRegistrationTransfer(): HeidelpayDirectDebitRegistrationTransfer
    {
        return (new HeidelpayDirectDebitRegistrationTransfer())
            ->setTransactionId(HeidelpayTestConfig::TRANSACTION_ID)
            ->setRegistrationUniqueId(HeidelpayTestConfig::REGISTRATION_NUMBER);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistration
     */
    protected function createDirectDebitRegistration(QuoteTransfer $quoteTransfer): SpyPaymentHeidelpayDirectDebitRegistration
    {
        $directDebitRegistrationBuilder = new DirectDebitRegistrationBuilder();

        return $directDebitRegistrationBuilder->createDirectDebitRegistration($quoteTransfer);
    }
}
