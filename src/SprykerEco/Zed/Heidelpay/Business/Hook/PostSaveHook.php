<?php
/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerEco\Zed\Heidelpay\Business\Hook;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class PostSaveHook implements PostSaveHookInterface
{

    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface[]
     */
    protected $paymentMethodsWithPostSaveOrderProcessing;

    /**
     * @param \Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface[] $paymentMethodsWithPostSaveOrderProcessing
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
    public function execute(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
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
     * @return boolean
     */
    protected function hasPaymentMethodPostSaveOrderProcessing($paymentMethod)
    {
        return isset($this->paymentMethodsWithPostSaveOrderProcessing[$paymentMethod]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Heidelpay\Business\Payment\BaseHeidelpayPaymentMethod
     */
    protected function getPaymentMethodFromQuote(QuoteTransfer $quoteTransfer)
    {
        $paymentMethodCode = $quoteTransfer->getPayment()->getPaymentMethod();
        return $this->paymentMethodsWithPostSaveOrderProcessing[$paymentMethodCode];
    }

}
