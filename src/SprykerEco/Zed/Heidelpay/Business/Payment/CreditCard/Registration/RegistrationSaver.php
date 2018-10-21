<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration;

use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface;

class RegistrationSaver implements RegistrationSaverInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface
     */
    private $heidelpayQueryContainer;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface $heidelpayQueryContainer
     */
    public function __construct(HeidelpayQueryContainerInterface $heidelpayQueryContainer)
    {
        $this->heidelpayQueryContainer = $heidelpayQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer
     */
    public function saveCreditCardRegistration(HeidelpayRegistrationRequestTransfer $registrationRequestTransfer): HeidelpayRegistrationSaveResponseTransfer
    {
        $spyCreditCardRegistration = $this->buildRegistrationEntityFromRequest($registrationRequestTransfer);
        $spyCreditCardRegistration->save();

        return $this->buildRegistrationSaveResponse($spyCreditCardRegistration);
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration
     */
    protected function buildRegistrationEntityFromRequest(
        HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
    ): SpyPaymentHeidelpayCreditCardRegistration {
        $spyCreditCardRegistration = new SpyPaymentHeidelpayCreditCardRegistration();

        $spyCreditCardRegistration->fromArray(
            $registrationRequestTransfer->getCreditCardInfo()->toArray()
        );

        $spyCreditCardRegistration
            ->setQuoteHash($registrationRequestTransfer->getQuoteHash())
            ->setRegistrationNumber($registrationRequestTransfer->getRegistrationHash());

        return $spyCreditCardRegistration;
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration $spyCreditCardRegistration
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer
     */
    protected function buildRegistrationSaveResponse(
        SpyPaymentHeidelpayCreditCardRegistration $spyCreditCardRegistration
    ): HeidelpayRegistrationSaveResponseTransfer {
        $registrationSaveResponse = new HeidelpayRegistrationSaveResponseTransfer();
        $registrationSaveResponse->setIdRegistration($spyCreditCardRegistration->getIdCreditCardRegistration());

        return $registrationSaveResponse;
    }
}
