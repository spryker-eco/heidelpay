<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Handler;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;

class HeidelpayHandler implements HeidelpayHandlerInterface
{
    const PAYMENT_PROVIDER = HeidelpayConfig::PROVIDER_NAME;
    const CHECKOUT_PARTIAL_SUMMARY_PATH = 'Heidelpay/partial/summary';

    /**
     * @var array
     */
    protected static $paymentMethods = [
        HeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE => HeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE,
        HeidelpayConfig::PAYMENT_METHOD_PAYPAL_DEBIT => HeidelpayConfig::PAYMENT_METHOD_PAYPAL_DEBIT,
        HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE,
        HeidelpayConfig::PAYMENT_METHOD_IDEAL => HeidelpayConfig::PAYMENT_METHOD_IDEAL,
        HeidelpayConfig::PAYMENT_METHOD_SOFORT => HeidelpayConfig::PAYMENT_METHOD_SOFORT,
    ];

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function addPaymentToQuote(AbstractTransfer $quoteTransfer)
    {
        $paymentSelection = $quoteTransfer->getPayment()->getPaymentSelection();
        $quoteTransfer->getPayment()
            ->setPaymentProvider(static::PAYMENT_PROVIDER)
            ->setPaymentMethod(static::$paymentMethods[$paymentSelection]);

        return $quoteTransfer;
    }
}
