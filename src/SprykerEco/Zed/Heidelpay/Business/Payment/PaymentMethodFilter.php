<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment;

use ArrayObject;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Heidelpay\HeidelpayConfig;

class PaymentMethodFilter implements PaymentMethodFilterInterface
{
    protected const HEIDELPAY_EASY_CREDIT_PAYMENT_METHOD = 'heidelpayEasyCredit';
    protected const GRAND_TOTAL_LESS_THAN = 200;
    protected const GRAND_TOTAL_MORE_THAN = 3000;

    /**
     * @var \SprykerEco\Zed\Heidelpay\HeidelpayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfig $config
     */
    public function __construct(HeidelpayConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(
        PaymentMethodsTransfer $paymentMethodsTransfer,
        QuoteTransfer $quoteTransfer
    ): PaymentMethodsTransfer {

        $result = new ArrayObject();
        $grandTotal = $quoteTransfer->getTotals()->getGrandTotal();
        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethod) {
            if ($this->isPaymentMethodHeidelpayEasyCredit($paymentMethod) && ($grandTotal < static::GRAND_TOTAL_LESS_THAN || $grandTotal > static::GRAND_TOTAL_MORE_THAN)) {
                continue;
            }

            $result->append($paymentMethod);
        }

        $paymentMethodsTransfer->setMethods($result);

        return $paymentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return bool
     */
    protected function isPaymentMethodHeidelpayEasyCredit(PaymentMethodTransfer $paymentMethodTransfer): bool
    {
        return $paymentMethodTransfer->getMethodName() === static::HEIDELPAY_EASY_CREDIT_PAYMENT_METHOD;
    }
}
