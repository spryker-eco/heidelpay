<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Heidelpay\PhpPaymentApi\Request;

class RequestToHeidelpay implements RequestToHeidelpayInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $requestTransfer
     * @param \Heidelpay\PhpPaymentApi\Request $heidelpayRequest
     *
     * @return void
     */
    public function map(HeidelpayRequestTransfer $requestTransfer, Request $heidelpayRequest)
    {
        $heidelpayRequest->async(
            $requestTransfer->getAsync()->getLanguageCode(),
            $requestTransfer->getAsync()->getResponseUrl()
        );

        $heidelpayRequest->authentification(
            $requestTransfer->getAuth()->getSecuritySender(),
            $requestTransfer->getAuth()->getUserLogin(),
            $requestTransfer->getAuth()->getUserPassword(),
            $requestTransfer->getAuth()->getTransactionChannel(),
            $requestTransfer->getAuth()->getIsSandboxRequest()
        );

        $heidelpayRequest->customerAddress(
            $requestTransfer->getCustomerAddress()->getFirstName(),
            $requestTransfer->getCustomerAddress()->getLastName(),
            $requestTransfer->getCustomerAddress()->getCompany(),
            $requestTransfer->getCustomerAddress()->getIdShopper(),
            $requestTransfer->getCustomerAddress()->getStreet(),
            $requestTransfer->getCustomerAddress()->getState(),
            $requestTransfer->getCustomerAddress()->getZip(),
            $requestTransfer->getCustomerAddress()->getCity(),
            $requestTransfer->getCustomerAddress()->getCountry(),
            $requestTransfer->getCustomerAddress()->getEmail()
        );

        $heidelpayRequest->basketData(
            $requestTransfer->getCustomerPurchase()->getIdOrder(),
            (string)$requestTransfer->getCustomerPurchase()->getAmount(),
            $requestTransfer->getCustomerPurchase()->getCurrencyCode(),
            $requestTransfer->getCustomerPurchase()->getSecret()
        );

        if ($requestTransfer->getCustomerPurchase()->getBasketId()) {
            $heidelpayRequest->getBasket()->setId($requestTransfer->getCustomerPurchase()->getBasketId());
        }
    }
}
