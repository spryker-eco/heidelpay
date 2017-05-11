<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heidelpay\Business\Payment\CreditCard\Registration;

use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration;
use Spryker\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface;

class RegistrationWriter implements RegistrationWriterInterface
{

    /**
     * @var \Spryker\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface
     */
    private $heidelpayQueryContainer;

    /**
     * @param \Spryker\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface $heidelpayQueryContainer
     */
    public function __construct(HeidelpayQueryContainerInterface $heidelpayQueryContainer)
    {
        $this->heidelpayQueryContainer = $heidelpayQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveRegistrationFromQuote(QuoteTransfer $quoteTransfer)
    {
        $registrationEntity = $this->fetchRegistrationFromQuote($quoteTransfer);

        if (!$this->isRegistrationExists($registrationEntity)) {
            $registrationEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration
     */
    protected function fetchRegistrationFromQuote(QuoteTransfer $quoteTransfer)
    {
        $registrationEntity = new SpyPaymentHeidelpayCreditCardRegistration();
        $this->fillRegistrationEntityFromQuoteTransfer($quoteTransfer, $registrationEntity);

        return $registrationEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration $registrationEntity
     *
     * @return void
     */
    protected function fillRegistrationEntityFromQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        SpyPaymentHeidelpayCreditCardRegistration $registrationEntity
    ) {
        $creditCardPayment = $quoteTransfer
            ->getPayment()
            ->getHeidelpayCreditCardSecure()
            ->getSelectedRegistration();

        $creditCardInfo = $creditCardPayment->getCreditCardInfo();

        $registrationEntity->setFkCustomerAddress($quoteTransfer->getShippingAddress()->getIdCustomerAddress())
            ->setAccountVerification($creditCardInfo->getAccountVerification())
            ->setAccountNumber($creditCardInfo->getAccountNumber())
            ->setAccountHolder($creditCardInfo->getAccountHolder())
            ->setAccountExpiryYear($creditCardInfo->getAccountExpiryYear())
            ->setAccountExpiryMonth($creditCardInfo->getAccountExpiryMonth())
            ->setAccountBrand($creditCardInfo->getAccountBrand())
            ->setRegistrationNumber($creditCardPayment->getRegistrationNumber());
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration $registrationEntity
     *
     * @return boolean
     */
    protected function isRegistrationExists(SpyPaymentHeidelpayCreditCardRegistration $registrationEntity)
    {
        return $this->heidelpayQueryContainer
            ->queryCreditCardRegistrationByIdRegistration(
                $registrationEntity->getRegistrationNumber()
            )
            ->exists();
    }

}
