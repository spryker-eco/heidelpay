<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Mapper;

use DateTime;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\HeidelpayCustomerAddressTransfer;
use Generated\Shared\Transfer\HeidelpayCustomerPurchaseTransfer;
use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRiskInformationTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyFacadeInterface;

class OrderToHeidelpayRequest implements OrderToHeidelpayRequestInterface
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function map(OrderTransfer $orderTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer): HeidelpayRequestTransfer
    {
        $heidelpayRequestTransfer = $this->mapCustomerAddress($orderTransfer, $heidelpayRequestTransfer);
        $heidelpayRequestTransfer = $this->mapOrderInformation($orderTransfer, $heidelpayRequestTransfer);
        $heidelpayRequestTransfer = $this->mapOrderPayment($orderTransfer, $heidelpayRequestTransfer);
        $heidelpayRequestTransfer = $this->mapCustomerInformation($orderTransfer, $heidelpayRequestTransfer);

        return $heidelpayRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function mapCustomerAddress(OrderTransfer $orderTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer): HeidelpayRequestTransfer
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
    protected function mapOrderInformation(OrderTransfer $orderTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer): HeidelpayRequestTransfer
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
    protected function getFullStreetName(AddressTransfer $shippingAddressTransfer): string
    {
        return sprintf('%s %s', $shippingAddressTransfer->getAddress1(), $shippingAddressTransfer->getAddress2());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return float
     */
    protected function getOrderGrandTotalInDecimal(OrderTransfer $orderTransfer): float
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
    protected function mapOrderPayment(OrderTransfer $orderTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer): HeidelpayRequestTransfer
    {
        $heidelpayPayment = $orderTransfer->getHeidelpayPayment();
        $heidelpayRequestTransfer
            ->setIdBasket($heidelpayPayment->getIdBasket())
            ->setIdPaymentRegistration($heidelpayPayment->getIdPaymentRegistration())
            ->setIdPaymentReference($heidelpayPayment->getIdPaymentReference());

        return $heidelpayRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function mapCustomerInformation(OrderTransfer $orderTransfer, HeidelpayRequestTransfer $heidelpayRequestTransfer): HeidelpayRequestTransfer
    {
        $heidelpayRequestTransfer->setRiskInformation(
            (new HeidelpayRiskInformationTransfer())
                ->setIsCustomerGuest((bool)$orderTransfer->getCustomer()->getIsGuest())
                ->setCustomerSince($this->findCustomerRegistrationDate($orderTransfer))
                ->setCustomerId($orderTransfer->getCustomer()->getIdCustomer())
        );

        return $heidelpayRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string|null
     */
    protected function findCustomerRegistrationDate(OrderTransfer $orderTransfer): ?string
    {
        if ($orderTransfer->getCustomer() === null || $orderTransfer->getCustomer()->getCreatedAt() === null) {
            return null;
        }

        return $this->formatDate(
            $orderTransfer->getCustomer()->getCreatedAt()
        );
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
}
