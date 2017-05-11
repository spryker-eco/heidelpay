<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heidelpay\Business\Payment\CreditCard\Registration;

use Generated\Shared\Transfer\HeidelpayCreditCardInfoTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration;
use Spryker\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface;

class RegistrationReader implements RegistrationReaderInterface
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
     * @return null|\Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer
     */
    public function getLastSuccessfulRegistrationForQuote(QuoteTransfer $quoteTransfer)
    {
        $lastSuccessfulRegistration = $this->findLastSuccessfulRegistrationByQuote($quoteTransfer);

        if ($lastSuccessfulRegistration === null) {
            return null;
        }

        $registrationTransfer = $this->getRegistrationTransferFromEntity($lastSuccessfulRegistration);

        return $registrationTransfer;
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration $lastSuccessfulRegistration
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer
     */
    protected function getRegistrationTransferFromEntity(
        SpyPaymentHeidelpayCreditCardRegistration $lastSuccessfulRegistration
    ) {
        $registrationTransfer = new HeidelpayCreditCardRegistrationTransfer();
        $creditCardInfo = (new HeidelpayCreditCardInfoTransfer())
            ->fromArray($lastSuccessfulRegistration->toArray(), true);

        $registrationTransfer
            ->setRegistrationNumber($lastSuccessfulRegistration->getRegistrationNumber())
            ->setCreditCardInfo($creditCardInfo)
            ->setIdCustomerAddress($lastSuccessfulRegistration->getFkCustomerAddress());

        return $registrationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration|null
     */
    protected function findLastSuccessfulRegistrationByQuote(QuoteTransfer $quoteTransfer)
    {
        $lastSuccessfulRegistration = $this->heidelpayQueryContainer
            ->queryLatestRegistrationByIdShippingAddress(
                $quoteTransfer->getShippingAddress()->getIdCustomerAddress()
            )
            ->findOne();

        return $lastSuccessfulRegistration;
    }

}
