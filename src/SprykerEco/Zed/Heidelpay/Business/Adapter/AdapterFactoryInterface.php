<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter;

use SprykerEco\Zed\Heidelpay\Business\Adapter\Basket\BasketInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\DirectDebitPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\EasyCreditPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\IdealPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\InvoiceSecuredB2cPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\PaypalPaymentInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPaymentInterface;

/**
 * @method \SprykerEco\Zed\Heidelpay\HeidelpayConfig getConfig()
 */
interface AdapterFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface[]
     */
    public function getAuthorizePaymentMethodAdapterCollection(): array;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithCaptureInterface[]
     */
    public function getCapturePaymentMethodAdapterCollection(): array;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitInterface[]
     */
    public function getDebitPaymentMethodAdapterCollection(): array;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithDebitOnRegistrationInterface[]
     */
    public function getDebitOnRegistrationPaymentMethodAdapterCollection(): array;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithFinalizeInterface[]
     */
    public function getFinalizePaymentMethodAdapterCollection(): array;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithReservationInterface[]
     */
    public function getReservationPaymentMethodAdapterCollection(): array;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithRefundInterface[]
     */
    public function getRefundPaymentMethodAdapterCollection(): array;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface[]
     */
    public function getExternalResponsePaymentMethodAdapterCollection(): array;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeOnRegistrationInterface[]
     */
    public function getAuthorizeOnRegistrationPaymentMethodAdapterCollection(): array;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithInitializeInterface[]
     */
    public function getInitializePaymentMethodAdapterCollection(): array;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\SofortPaymentInterface
     */
    public function createSofortPaymentMethodAdapter(): SofortPaymentInterface;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\IdealPaymentInterface
     */
    public function createIdealPaymentMethodAdapter(): IdealPaymentInterface;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\EasyCreditPaymentInterface
     */
    public function createEasyCreditPaymentMethodAdapter(): EasyCreditPaymentInterface;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\PaypalPaymentInterface
     */
    public function createPaypalPaymentMethodAdapter(): PaypalPaymentInterface;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\CreditCardPaymentInterface
     */
    public function createCreditCardPaymentMethodAdapter(): CreditCardPaymentInterface;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\InvoiceSecuredB2cPaymentInterface
     */
    public function createInvoiceSecuredB2cPayment(): InvoiceSecuredB2cPaymentInterface;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Payment\DirectDebitPaymentInterface
     */
    public function createDirectDebitPaymentMethod(): DirectDebitPaymentInterface;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\TransactionParserInterface
     */
    public function createTransactionParser(): TransactionParserInterface;

    /**
     * @return \SprykerEco\Zed\Heidelpay\Business\Adapter\Basket\BasketInterface
     */
    public function createBasketAdapter(): BasketInterface;
}
