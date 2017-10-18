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
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyInterface;

class OrderToHeidelpayRequest implements OrderToHeidelpayRequestInterface
{

    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyInterface $moneyFacade
     */
    public function __construct(HeidelpayToMoneyInterface $moneyFacade)
    {
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function map(OrderTransfer $orderTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer)
    {
        $heidelpayRequestTransfer = $this->mapCustomerAddress($orderTransfer, $heidelpayRequestTransfer);
        $heidelpayRequestTransfer = $this->mapOrderInformation($orderTransfer, $heidelpayRequestTransfer);
        $heidelpayRequestTransfer = $this->mapOrderPayment($orderTransfer, $heidelpayRequestTransfer);

        return $heidelpayRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function mapCustomerAddress(OrderTransfer $orderTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer)
    {
        $heidelpayRequestTransfer->setCustomerAddress(
            (new HeidelpayCustomerAddressTransfer())
                ->setCity($orderTransfer->getBillingAddress()->getCity())
                ->setCompany($orderTransfer->getBillingAddress()->getCompany())
                ->setCountry($orderTransfer->getBillingAddress()->getIso2Code())
                ->setEmail($orderTransfer->getEmail())
                ->setFirstName($orderTransfer->getBillingAddress()->getFirstName())
                ->setLastName($orderTransfer->getBillingAddress()->getLastName())
                ->setState($orderTransfer->getBillingAddress()->getState())
                ->setStreet($this->getFullStreetName($orderTransfer->getBillingAddress()))
                ->setZip($orderTransfer->getBillingAddress()->getZipCode())
        );

        return $heidelpayRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function mapOrderInformation(OrderTransfer $orderTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer)
    {
        $heidelpayRequestTransfer->setCustomerPurchase(
            (new HeidelpayCustomerPurchaseTransfer())
                ->setAmount($this->getOrderGrandTotalInDecimal($orderTransfer))
                ->setIdOrder((string)$orderTransfer->getIdSalesOrder())
        );

        return $heidelpayRequestTransfer;
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return float
     */
    protected function getOrderGrandTotalInDecimal(OrderTransfer $orderTransfer)
    {
        $orderAmountInt = $orderTransfer->getTotals()->getGrandTotal();
        $orderAmountDecimal = $this->moneyFacade->convertIntegerToDecimal($orderAmountInt);

        return $orderAmountDecimal;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function mapOrderPayment(OrderTransfer $orderTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer)
    {
        $heidelpayPayment = $orderTransfer->getHeidelpayPayment();
        $heidelpayRequestTransfer->setIdPaymentRegistration($heidelpayPayment->getIdPaymentRegistration());

        return $heidelpayRequestTransfer;
    }

}
