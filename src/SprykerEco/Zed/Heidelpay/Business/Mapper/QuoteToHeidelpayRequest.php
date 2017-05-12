<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerEco\Zed\Heidelpay\Business\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\HeidelpayCustomerAddressTransfer;
use Generated\Shared\Transfer\HeidelpayCustomerPurchaseTransfer;
use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyInterface;

class QuoteToHeidelpayRequest implements QuoteToHeidelpayRequestInterface
{

    /**
     * @var \Spryker\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyInterface $moneyFacade
     */
    public function __construct(HeidelpayToMoneyInterface $moneyFacade)
    {
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function map(QuoteTransfer $quoteTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer)
    {
        $heidelpayRequestTransfer = $this->mapCustomerAddress($quoteTransfer, $heidelpayRequestTransfer);
        $heidelpayRequestTransfer = $this->mapQuoteInformation($quoteTransfer, $heidelpayRequestTransfer);

        return $heidelpayRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function mapCustomerAddress(QuoteTransfer $quoteTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer)
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
    protected function mapQuoteInformation(QuoteTransfer $quoteTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer)
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
     *
     * @return string
     */
    protected function generateQuoteId(QuoteTransfer $quoteTransfer)
    {
        $quoteId = $quoteTransfer->getCustomer()->getEmail() . $quoteTransfer->getTotals()->getGrandTotal();

        return uniqid($quoteId);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $shippingAddressTransfer
     *
     * @return string
     */
    protected function getFullStreetName(AddressTransfer $shippingAddressTransfer)
    {
        return sprintf('%s %s', $shippingAddressTransfer->getAddress1(), $shippingAddressTransfer->getAddress2());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getQuoteAmount(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getTotals()->getGrandTotal();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return float
     */
    protected function getDecimalQuoteAmount(QuoteTransfer $quoteTransfer)
    {
        $quoteAmountInt = $quoteTransfer->getTotals()->getGrandTotal();
        $quoteAmountDecimal = $this->moneyFacade->convertIntegerToDecimal($quoteAmountInt);

        return $quoteAmountDecimal;
    }

}
