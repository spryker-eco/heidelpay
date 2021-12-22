<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use DateTime;
use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Heidelpay\PhpPaymentApi\PaymentMethods\InvoiceB2CSecuredPaymentMethod;

class InvoiceSecuredB2cPayment extends BasePayment implements InvoiceSecuredB2cPaymentInterface
{
    /**
     * @var string
     */
    protected const FORMAT_DATE_OF_BIRTH = 'Y-m-d';

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorize(HeidelpayRequestTransfer $authorizeRequestTransfer): HeidelpayResponseTransfer
    {
        $invoiceSecuredB2cMethod = new InvoiceB2CSecuredPaymentMethod();
        $this->prepareRequest($authorizeRequestTransfer, $invoiceSecuredB2cMethod->getRequest());
        $invoiceSecuredB2cMethod = $this->addAdditionalCustomerInformation($authorizeRequestTransfer, $invoiceSecuredB2cMethod);
        $invoiceSecuredB2cMethod->authorize();

        return $this->verifyAndParseResponse($invoiceSecuredB2cMethod->getResponse());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $finalizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function finalize(HeidelpayRequestTransfer $finalizeRequestTransfer): HeidelpayResponseTransfer
    {
        $invoiceSecuredB2cMethod = new InvoiceB2CSecuredPaymentMethod();
        $this->prepareRequest($finalizeRequestTransfer, $invoiceSecuredB2cMethod->getRequest());
        $invoiceSecuredB2cMethod->finalize($finalizeRequestTransfer->getIdPaymentReference());

        return $this->verifyAndParseResponse($invoiceSecuredB2cMethod->getResponse());
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     * @param \Heidelpay\PhpPaymentApi\PaymentMethods\InvoiceB2CSecuredPaymentMethod $invoiceSecuredB2cMethod
     *
     * @return \Heidelpay\PhpPaymentApi\PaymentMethods\InvoiceB2CSecuredPaymentMethod
     */
    protected function addAdditionalCustomerInformation(
        HeidelpayRequestTransfer $authorizeRequestTransfer,
        InvoiceB2CSecuredPaymentMethod $invoiceSecuredB2cMethod
    ): InvoiceB2CSecuredPaymentMethod {
        $invoiceSecuredB2cMethod->getRequest()->b2cSecured(
            $authorizeRequestTransfer->getInvoiceSecuredB2c()->getSalutation(),
            $this->getFormattedDateOfBirth($authorizeRequestTransfer),
            $authorizeRequestTransfer->getIdBasket(),
        );

        return $invoiceSecuredB2cMethod;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return string
     */
    protected function getFormattedDateOfBirth(HeidelpayRequestTransfer $authorizeRequestTransfer): string
    {
        $date = new DateTime($authorizeRequestTransfer->getInvoiceSecuredB2c()->getDateOfBirth());

        return $date->format(static::FORMAT_DATE_OF_BIRTH);
    }
}
