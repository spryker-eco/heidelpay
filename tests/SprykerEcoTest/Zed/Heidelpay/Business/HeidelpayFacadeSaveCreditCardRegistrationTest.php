<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\HeidelpayCreditCardInfoTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer;
use Orm\Zed\Heidelpay\Persistence\Base\SpyPaymentHeidelpayCreditCardRegistration;
use Orm\Zed\Heidelpay\Persistence\Base\SpyPaymentHeidelpayCreditCardRegistrationQuery;
use Propel\Runtime\Propel;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory;
use SprykerEco\Zed\Heidelpay\Business\HeidelpayFacade;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Heidelpay
 * @group Business
 * @group HeidelpayFacadeSaveCreditCardRegistrationTest
 */
class HeidelpayFacadeSaveCreditCardRegistrationTest extends Test
{
    const QUOTE_HASH = 'bea74ee13da897592f633fc93024ab3f5231d74d';
    const REGISTRATION_HASH = '31HA07BC814CA0300B131ABC71AEECB3';
    const ACCOUNT_EXPIRY_YEAR = 2030;
    const ACCOUNT_EXPIRY_MONTH = '03';
    const ACCOUNT_HOLDER_NAME = 'John Doe';
    const CREDIT_CARD_BRAND = 'MASTER';
    const CREDIT_CARD_NUMBER = '471110******0000';

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
            ->setFactory($this->createHeidelpayFactory());

        $this->getModule('\\' . ConfigHelper::class)
            ->setConfig(HeidelpayConstants::CONFIG_ENCRYPTION_KEY, 'encryption_key');
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\HeidelpayBusinessFactory
     */
    protected function createHeidelpayFactory()
    {
        return new HeidelpayBusinessFactory();
    }

    /**
     * @dataProvider _createRegistrationTransfer
     *
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $cardRegistrationTransfer
     */
    public function testSuccessfulSaveCreditCardRegistration(HeidelpayRegistrationRequestTransfer $cardRegistrationTransfer)
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
    public function _createRegistrationTransfer()
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
    protected function getCreditCardInfo()
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