<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration;

use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface;

class RegistrationWriter implements RegistrationWriterInterface
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
     * @return void
     */
    public function updateRegistrationWithAddressIdFromQuote(QuoteTransfer $quoteTransfer): void
    {
        $registrationEntity = $this->findRegistrationFromQuote($quoteTransfer);

        if ($registrationEntity !== null) {
            $registrationEntity
                ->setFkCustomerAddress(
                    $quoteTransfer->getShippingAddress()->getIdCustomerAddress()
                )
                ->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration
     */
    protected function findRegistrationFromQuote(QuoteTransfer $quoteTransfer): SpyPaymentHeidelpayCreditCardRegistration
    {
        $registrationHash = $quoteTransfer
            ->getPayment()
            ->getHeidelpayCreditCardSecure()
            ->getSelectedRegistration()
            ->getRegistrationNumber();

        $registrationEntity = $this->heidelpayQueryContainer
            ->queryCreditCardRegistrationByRegistrationNumber(
                $registrationHash
            )
            ->findOne();

        return $registrationEntity;
    }
}
