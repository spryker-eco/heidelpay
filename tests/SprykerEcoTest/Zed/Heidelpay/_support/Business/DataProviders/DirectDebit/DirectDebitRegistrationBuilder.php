<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\DirectDebit;

use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistration;
use SprykerEcoTest\Zed\Heidelpay\HeidelpayTestConfig;

class DirectDebitRegistrationBuilder
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistration
     */
    public function createDirectDebitRegistration(QuoteTransfer $quoteTransfer): SpyPaymentHeidelpayDirectDebitRegistration
    {
        $directDebitRegistrationEntity = new SpyPaymentHeidelpayDirectDebitRegistration();

        $directDebitRegistrationEntity
            ->setFkCustomerAddress($quoteTransfer->getShippingAddress()->getIdCustomerAddress())
            ->setRegistrationUniqueId(HeidelpayTestConfig::REGISTRATION_NUMBER)
            ->setAccountBankName(HeidelpayTestConfig::ACCOUNT_BANK_NAME)
            ->setAccountBic(HeidelpayTestConfig::ACCOUNT_BIC)
            ->setAccountCountry(HeidelpayTestConfig::ACCOUNT_COUNTRY)
            ->setAccountHolder($this->getAccountHolder($quoteTransfer))
            ->setAccountIban(HeidelpayTestConfig::ACCOUNT_IBAN)
            ->setAccountNumber(HeidelpayTestConfig::ACCOUNT_NUMBER)
            ->setTransactionId(HeidelpayTestConfig::TRANSACTION_ID);

        $directDebitRegistrationEntity->save();

        return $directDebitRegistrationEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getAccountHolder(QuoteTransfer $quoteTransfer): string
    {
        return vsprintf(
            '%s %s',
            [
                $quoteTransfer->getCustomer()->getFirstName(),
                $quoteTransfer->getCustomer()->getLastName(),
            ],
        );
    }
}
