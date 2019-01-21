<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Handler;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;

class HeidelpayHandler implements HeidelpayHandlerInterface
{
    public const PAYMENT_PROVIDER = HeidelpayConfig::PROVIDER_NAME;
    public const CHECKOUT_PARTIAL_SUMMARY_PATH = 'Heidelpay/partial/summary';

    /**
     * @var array
     */
    protected static $paymentMethods = [
        HeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE => HeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE,
        HeidelpayConfig::PAYMENT_METHOD_PAYPAL_DEBIT => HeidelpayConfig::PAYMENT_METHOD_PAYPAL_DEBIT,
        HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE,
        HeidelpayConfig::PAYMENT_METHOD_IDEAL => HeidelpayConfig::PAYMENT_METHOD_IDEAL,
        HeidelpayConfig::PAYMENT_METHOD_SOFORT => HeidelpayConfig::PAYMENT_METHOD_SOFORT,
        HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT => HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT,
    ];

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        $paymentSelection = $quoteTransfer->getPayment()->getPaymentSelection();
        $quoteTransfer->getPayment()
            ->setPaymentProvider(static::PAYMENT_PROVIDER)
            ->setPaymentMethod(static::$paymentMethods[$paymentSelection]);

        return $quoteTransfer;
    }
}
