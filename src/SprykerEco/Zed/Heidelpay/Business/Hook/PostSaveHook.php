<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Hook;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class PostSaveHook implements PostSaveHookInterface
{
    /**
     * @var array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface>
     */
    protected $paymentMethodsWithPostSaveOrderProcessing;

    /**
     * @param array<\SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface> $paymentMethodsWithPostSaveOrderProcessing
     */
    public function __construct(array $paymentMethodsWithPostSaveOrderProcessing)
    {
        $this->paymentMethodsWithPostSaveOrderProcessing = $paymentMethodsWithPostSaveOrderProcessing;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): CheckoutResponseTransfer
    {
        $paymentMethodCode = $quoteTransfer->getPayment()->getPaymentMethod();

        if ($this->hasPaymentMethodPostSaveOrderProcessing($paymentMethodCode)) {
            $paymentMethod = $this->paymentMethodsWithPostSaveOrderProcessing[$paymentMethodCode];
            $paymentMethod->postSaveOrder($quoteTransfer, $checkoutResponseTransfer);
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param string $paymentMethod
     *
     * @return bool
     */
    protected function hasPaymentMethodPostSaveOrderProcessing(string $paymentMethod): bool
    {
        return isset($this->paymentMethodsWithPostSaveOrderProcessing[$paymentMethod]);
    }
}
