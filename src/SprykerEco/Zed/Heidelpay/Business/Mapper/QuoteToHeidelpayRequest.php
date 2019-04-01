<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\HeidelpayCustomerAddressTransfer;
use Generated\Shared\Transfer\HeidelpayCustomerPurchaseTransfer;
use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRiskInformationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Heidelpay\QuoteUniqueIdGenerator;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyFacadeInterface;

class QuoteToHeidelpayRequest implements QuoteToHeidelpayRequestInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(HeidelpayToMoneyFacadeInterface $moneyFacade)
    {
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function map(QuoteTransfer $quoteTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer): HeidelpayRequestTransfer
    {
        $heidelpayRequestTransfer = $this->mapCustomerAddress($quoteTransfer, $heidelpayRequestTransfer);
        $heidelpayRequestTransfer = $this->mapQuoteInformation($quoteTransfer, $heidelpayRequestTransfer);
        $heidelpayRequestTransfer = $this->mapCustoemrInformation($quoteTransfer, $heidelpayRequestTransfer);

        return $heidelpayRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function mapCustomerAddress(QuoteTransfer $quoteTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer): HeidelpayRequestTransfer
    {
        $heidelpayRequestTransfer->setCustomerAddress(
            (new HeidelpayCustomerAddressTransfer())
                ->setCity($quoteTransfer->getBillingAddress()->getCity())
                ->setCompany($quoteTransfer->getBillingAddress()->getCompany())
                ->setCountry($quoteTransfer->getBillingAddress()->getIso2Code())
                ->setEmail($quoteTransfer->getCustomer()->getEmail())
                ->setFirstName($quoteTransfer->getBillingAddress()->getFirstName())
                ->setLastName($quoteTransfer->getBillingAddress()->getLastName())
                ->setState($quoteTransfer->getBillingAddress()->getState())
                ->setStreet($this->getFullStreetName($quoteTransfer->getBillingAddress()))
                ->setZip($quoteTransfer->getBillingAddress()->getZipCode())
        );

        return $heidelpayRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function mapQuoteInformation(QuoteTransfer $quoteTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer): HeidelpayRequestTransfer
    {
        $heidelpayRequestTransfer->setCustomerPurchase(
            (new HeidelpayCustomerPurchaseTransfer())
                ->setAmount($this->getDecimalQuoteAmount($quoteTransfer))
                ->setIdOrder($this->generateQuoteId($quoteTransfer))
        );

        return $heidelpayRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function mapCustoemrInformation(QuoteTransfer $quoteTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer): HeidelpayRequestTransfer
    {
        $customerRegistrationDate = $this->findCustomerRegistrationDate($quoteTransfer->getCustomer());

        $heidelpayRequestTransfer->setRiskInformation(
            (new HeidelpayRiskInformationTransfer())
                ->setIsCustomerGuest((bool)$quoteTransfer->getCustomer()->getIsGuest())
                ->setCustomerSince($customerRegistrationDate)
                ->setCustomerId($quoteTransfer->getCustomer()->getIdCustomer())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string|null
     */
    protected function findCustomerRegistrationDate(CustomerTransfer $customerTransfer): ?string
    {
        $createdAtDate = $customerTransfer->getCreatedAt();
        $createdAtDateFormatted = $createdAtDate ? $this->formatDate($createdAtDate) : $createdAtDate;

        return $createdAtDateFormatted;
    }

    /**
     * @param string $date
     *
     * @return string
     */
    protected function formatDate(string $date): string
    {
        return (new DateTime($date))->format('Y-m-d');
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function generateQuoteId(QuoteTransfer $quoteTransfer): string
    {
        return QuoteUniqueIdGenerator::getHashByCustomerEmailAndTotals($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $shippingAddressTransfer
     *
     * @return string
     */
    protected function getFullStreetName(AddressTransfer $shippingAddressTransfer): string
    {
        return sprintf('%s %s', $shippingAddressTransfer->getAddress1(), $shippingAddressTransfer->getAddress2());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getQuoteAmount(QuoteTransfer $quoteTransfer): int
    {
        return $quoteTransfer->getTotals()->getGrandTotal();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return float
     */
    protected function getDecimalQuoteAmount(QuoteTransfer $quoteTransfer): float
    {
        $quoteAmountInt = $quoteTransfer->getTotals()->getGrandTotal();
        $quoteAmountDecimal = $this->moneyFacade->convertIntegerToDecimal($quoteAmountInt);

        return $quoteAmountDecimal;
    }
}
