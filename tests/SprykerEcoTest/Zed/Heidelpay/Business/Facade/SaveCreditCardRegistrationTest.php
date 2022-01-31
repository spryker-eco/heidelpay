<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Generated\Shared\Transfer\HeidelpayCreditCardInfoTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer;
use Orm\Zed\Heidelpay\Persistence\Base\SpyPaymentHeidelpayCreditCardRegistrationQuery;

/**
 * @group Functional
 * @group SprykerEcoTest
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group Facade
 * @group SaveCreditCardRegistrationTest
 */
class SaveCreditCardRegistrationTest extends HeidelpayPaymentTest
{
    /**
     * @var string
     */
    protected const QUOTE_HASH = 'bea74ee13da897592f633fc93024ab3f5231d74d';

    /**
     * @var string
     */
    protected const REGISTRATION_HASH = '31HA07BC814CA0300B131ABC71AEECB3';

    /**
     * @var int
     */
    protected const ACCOUNT_EXPIRY_YEAR = 2030;

    /**
     * @var string
     */
    protected const ACCOUNT_EXPIRY_MONTH = '03';

    /**
     * @var string
     */
    protected const ACCOUNT_HOLDER_NAME = 'John Doe';

    /**
     * @var string
     */
    protected const CREDIT_CARD_BRAND = 'MASTER';

    /**
     * @var string
     */
    protected const CREDIT_CARD_NUMBER = '471110******0000';

    /**
     * @dataProvider createRegistrationTransfer
     *
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $cardRegistrationTransfer
     *
     * @return void
     */
    public function testSuccessfulSaveCreditCardRegistration(HeidelpayRegistrationRequestTransfer $cardRegistrationTransfer): void
    {
        // Act
        $response = $this->heidelpayFacade->saveCreditCardRegistration($cardRegistrationTransfer);

        // Assert
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
            ->setRegistrationHash(static::REGISTRATION_HASH)
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
