<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayCreditCardInfoTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer;
use Orm\Zed\Heidelpay\Persistence\Base\SpyPaymentHeidelpayCreditCardRegistrationQuery;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeSaveCreditCardRegistrationTest
 */
class HeidelpayFacadeSaveCreditCardRegistrationTest extends HeidelpayPaymentTest
{
    const QUOTE_HASH = 'bea74ee13da897592f633fc93024ab3f5231d74d';
    const REGISTRATION_HASH = '31HA07BC814CA0300B131ABC71AEECB3';
    const ACCOUNT_EXPIRY_YEAR = 2030;
    const ACCOUNT_EXPIRY_MONTH = '03';
    const ACCOUNT_HOLDER_NAME = 'John Doe';
    const CREDIT_CARD_BRAND = 'MASTER';
    const CREDIT_CARD_NUMBER = '471110******0000';

    /**
     * @dataProvider createRegistrationTransfer
     *
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $cardRegistrationTransfer
     *
     * @return void
     */
    public function testSuccessfulSaveCreditCardRegistration(HeidelpayRegistrationRequestTransfer $cardRegistrationTransfer): void
    {
        $response = $this->heidelpayFacade->saveCreditCardRegistration($cardRegistrationTransfer);

        $this->assertNotNull($response);
        $this->assertInstanceOf(HeidelpayRegistrationSaveResponseTransfer::class, $response);
        $this->assertNull($response->getIsError());
        $this->assertNull($response->getError());
        $this->assertNotNull($response->getIdRegistration());

        $cardRegistration = SpyPaymentHeidelpayCreditCardRegistrationQuery::create()
            ->findByIdCreditCardRegistration($response->getIdRegistration());

        $this->assertGreaterThan(0, count($cardRegistration->toArray()));
    }

    /**
     * @return array
     */
    public function createRegistrationTransfer(): array
    {
        $cardRegistrationTransfer = new HeidelpayRegistrationRequestTransfer();
        $cardRegistrationTransfer->setQuoteHash(static::QUOTE_HASH)
            ->setRegistrationHash(self::REGISTRATION_HASH)
            ->setCreditCardInfo($this->getCreditCardInfo());

        return [[$cardRegistrationTransfer]];
    }

    /**
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardInfoTransfer
     */
    protected function getCreditCardInfo(): HeidelpayCreditCardInfoTransfer
    {
        $creditCardInfo = new HeidelpayCreditCardInfoTransfer();

        $creditCardInfo->setAccountExpiryYear(static::ACCOUNT_EXPIRY_YEAR)
            ->setAccountExpiryMonth(static::ACCOUNT_EXPIRY_MONTH)
            ->setAccountHolder(static::ACCOUNT_HOLDER_NAME)
            ->setAccountBrand(static::CREDIT_CARD_BRAND)
            ->setAccountNumber(static::CREDIT_CARD_NUMBER);

        return $creditCardInfo;
    }
}
