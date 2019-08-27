<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayDirectDebitAccountTransfer;
use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepository;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface;
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConfig;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeSaveDirectDebitRegistrationTest
 */
class HeidelpayFacadeSaveDirectDebitRegistrationTest extends HeidelpayPaymentTest
{
    protected const TRANSACTION_ID = 'bea74ee13da897592f633fc93024ab3f5231d74d';
    protected const REGISTRATION_UNIQUE_ID = '31HA07BC814CA0300B131ABC71AEECB3';
    protected const ACCOUNT_HOLDER_NAME = 'John Doe';

    /**
     * @return void
     */
    public function testSuccessfulSaveDirectDebitRegistration(): void
    {
        //Arrange
        $directDebitRegistration = $this->createRegistrationTransfer();

        //Act
        $this->heidelpayFacade->saveDirectDebitRegistration($directDebitRegistration);
        $directDebitRegistration = $this->getRepository()
            ->findHeidelpayDirectDebitRegistrationByRegistrationUniqueId(static::REGISTRATION_UNIQUE_ID);

        //Assert
        $this->assertInstanceOf(HeidelpayDirectDebitRegistrationTransfer::class, $directDebitRegistration);
        $this->assertNull($directDebitRegistration->getIsError());
        $this->assertNull($directDebitRegistration->getError());
        $this->assertNotNull($directDebitRegistration->getIdDirectDebitRegistration());
    }

    /**
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function createRegistrationTransfer(): HeidelpayDirectDebitRegistrationTransfer
    {
        return (new HeidelpayDirectDebitRegistrationTransfer())
            ->setTransactionId(static::TRANSACTION_ID)
            ->setRegistrationUniqueId(static::REGISTRATION_UNIQUE_ID)
            ->setAccountInfo($this->createDirectDebitAccountTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitAccountTransfer
     */
    protected function createDirectDebitAccountTransfer(): HeidelpayDirectDebitAccountTransfer
    {
        return (new HeidelpayDirectDebitAccountTransfer())
            ->setAccountBankName(HeidelpayTestConfig::ACCOUNT_BANK_NAME)
            ->setAccountBic(HeidelpayTestConfig::ACCOUNT_BIC)
            ->setAccountCountry(HeidelpayTestConfig::ACCOUNT_COUNTRY)
            ->setAccountIban(HeidelpayTestConfig::ACCOUNT_IBAN)
            ->setAccountNumber(HeidelpayTestConfig::ACCOUNT_NUMBER)
            ->setAccountHolder(static::ACCOUNT_HOLDER_NAME);
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayRepositoryInterface
     */
    public function getRepository(): HeidelpayRepositoryInterface
    {
        return new HeidelpayRepository();
    }
}
