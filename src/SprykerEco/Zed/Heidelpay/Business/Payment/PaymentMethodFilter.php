<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment;

use ArrayObject;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayConfig;

class PaymentMethodFilter implements PaymentMethodFilterInterface
{
    protected const HEIDELPAY_EASY_CREDIT_PAYMENT_METHOD = 'heidelpayEasyCredit';

    protected const PACKSTATION = 'Packstation';

    protected const GRAND_TOTAL_LESS_THAN = 200;

    protected const GRAND_TOTAL_MORE_THAN = 3000;

    /**
     * @var \SprykerEco\Zed\Heidelpay\HeidelpayConfig
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfig $config
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyInterface $moneyFacade
     */
    public function __construct(
        HeidelpayConfig $config,
        HeidelpayToMoneyInterface $moneyFacade
    ) {
        $this->config = $config;
        $this->moneyFacade = $moneyFacade;
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
        $grandTotal = $this->moneyFacade->convertIntegerToDecimal($quoteTransfer->getTotals()->getGrandTotal());
        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethod) {
            $address = $this->isAddressCorrect($quoteTransfer);
            if ($this->isPaymentMethodHeidelpayEasyCredit($paymentMethod) && $this->isTotalOutOfRange($grandTotal) && $this->isAddressCorrect($quoteTransfer)) {
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

    /**
     * @param float $grandTotal
     *
     * @return bool
     */
    protected function isTotalOutOfRange(float $grandTotal): bool
    {
        return $grandTotal < static::GRAND_TOTAL_LESS_THAN || $grandTotal > static::GRAND_TOTAL_MORE_THAN;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isAddressCorrect(QuoteTransfer $quoteTransfer): bool
    {
        return strpos($quoteTransfer->getShippingAddress()->getAddress1(), static::PACKSTATION) === false
            && strpos($quoteTransfer->getBillingAddress()->getAddress1(), static::PACKSTATION) === false;
    }
}
