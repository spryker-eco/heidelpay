<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
    public function map(HeidelpayRequestTransfer $requestTransfer, Request $heidelpayRequest): void
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
            $requestTransfer->getCustomerPurchase()->getAmount(),
            $requestTransfer->getCustomerPurchase()->getCurrencyCode(),
            $requestTransfer->getCustomerPurchase()->getSecret()
        );

        $heidelpayRequest->getBasket()
            ->setId($requestTransfer->getIdBasket());
    }
}
