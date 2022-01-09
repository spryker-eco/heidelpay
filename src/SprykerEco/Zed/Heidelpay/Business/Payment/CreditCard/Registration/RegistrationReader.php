<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration;

use Generated\Shared\Transfer\HeidelpayCreditCardInfoTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration;
use SprykerEco\Shared\Heidelpay\QuoteUniqueIdGenerator;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface;

class RegistrationReader implements RegistrationReaderInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface
     */
    protected $heidelpayQueryContainer;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface $heidelpayQueryContainer
     */
    public function __construct(HeidelpayQueryContainerInterface $heidelpayQueryContainer)
    {
        $this->heidelpayQueryContainer = $heidelpayQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer
     */
    public function getLastSuccessfulRegistrationForQuote(QuoteTransfer $quoteTransfer): HeidelpayCreditCardRegistrationTransfer
    {
        $lastSuccessfulRegistration = $this->findLastSuccessfulRegistrationByQuote($quoteTransfer);

        if ($lastSuccessfulRegistration === null) {
            return $this->createEmptyRegistrationTransfer();
        }

        $registrationTransfer = $this->getRegistrationTransferFromEntity($lastSuccessfulRegistration);

        return $registrationTransfer;
    }

    /**
     * @param int $idRegistration
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer
     */
    public function findCreditCardRegistrationByIdAndQuote(int $idRegistration, QuoteTransfer $quoteTransfer): HeidelpayCreditCardRegistrationTransfer
    {
        $quoteHash = $this->generateQuoteHash($quoteTransfer);
        $registrationEntity = $this->heidelpayQueryContainer
            ->queryRegistrationByIdAndQuoteHash(
                $idRegistration,
                $quoteHash,
            )
            ->findOne();

        if ($registrationEntity === null) {
            return $this->createEmptyRegistrationTransfer();
        }

        $registrationTransfer = $this->getRegistrationTransferFromEntity($registrationEntity);

        return $registrationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function generateQuoteHash(QuoteTransfer $quoteTransfer): string
    {
        return QuoteUniqueIdGenerator::getHashByCustomerEmailAndTotals($quoteTransfer);
    }

    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration $lastSuccessfulRegistration
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer
     */
    protected function getRegistrationTransferFromEntity(
        SpyPaymentHeidelpayCreditCardRegistration $lastSuccessfulRegistration
    ): HeidelpayCreditCardRegistrationTransfer {
        $registrationTransfer = new HeidelpayCreditCardRegistrationTransfer();
        $creditCardInfo = (new HeidelpayCreditCardInfoTransfer())
            ->fromArray($lastSuccessfulRegistration->toArray(), true);

        $registrationTransfer
            ->setIdCreditCardRegistration($lastSuccessfulRegistration->getIdCreditCardRegistration())
            ->setRegistrationNumber($lastSuccessfulRegistration->getRegistrationNumber())
            ->setCreditCardInfo($creditCardInfo)
            ->setIdCustomerAddress($lastSuccessfulRegistration->getFkCustomerAddress());

        return $registrationTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer
     */
    protected function createEmptyRegistrationTransfer(): HeidelpayCreditCardRegistrationTransfer
    {
        return new HeidelpayCreditCardRegistrationTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration|null
     */
    protected function findLastSuccessfulRegistrationByQuote(QuoteTransfer $quoteTransfer): ?SpyPaymentHeidelpayCreditCardRegistration
    {
        $lastSuccessfulRegistration = $this->heidelpayQueryContainer
            ->queryLatestRegistrationByIdShippingAddress(
                $quoteTransfer->getShippingAddress()->getIdCustomerAddress(),
            )
            ->findOne();

        return $lastSuccessfulRegistration;
    }
}
