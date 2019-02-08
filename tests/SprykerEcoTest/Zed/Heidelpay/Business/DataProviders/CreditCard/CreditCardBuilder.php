<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\CreditCard;

use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration;
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConstants;

class CreditCardBuilder
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistration
     */
    public function createCreditCard(QuoteTransfer $quoteTransfer): SpyPaymentHeidelpayCreditCardRegistration
    {
        $cardRegistrationEntity = new SpyPaymentHeidelpayCreditCardRegistration();
        $cardRegistrationEntity
            ->setFkCustomerAddress($quoteTransfer->getShippingAddress()->getIdCustomerAddress())
            ->setRegistrationNumber(HeidelpayTestConstants::REGISTRATION_NUMBER)
            ->setAccountBrand(HeidelpayTestConstants::CARD_BRAND)
            ->setAccountExpiryMonth(1)
            ->setAccountExpiryYear(2030)
            ->setAccountNumber(HeidelpayTestConstants::CARD_ACCOUNT_NUMBER)
            ->setQuoteHash(HeidelpayTestConstants::CARD_QUOTE_HASH)
            ->setAccountHolder($this->getAccountHolder($quoteTransfer));
        $cardRegistrationEntity->save();

        return $cardRegistrationEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $qouteTransfer
     *
     * @return string
     */
    protected function getAccountHolder(QuoteTransfer $qouteTransfer): string
    {
        return vsprintf(
            "%s %s",
            [
                $qouteTransfer->getCustomer()->getFirstName(),
                $qouteTransfer->getCustomer()->getLastName(),
            ]
        );
    }
}
