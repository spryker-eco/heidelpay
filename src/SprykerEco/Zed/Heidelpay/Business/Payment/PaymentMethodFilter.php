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
use SprykerEco\Shared\Heidelpay\HeidelpayConfig as SharedHeidelpayConfig;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyFacadeInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayConfig;

class PaymentMethodFilter implements PaymentMethodFilterInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\HeidelpayConfig
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfig $config
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(
        HeidelpayConfig $config,
        HeidelpayToMoneyFacadeInterface $moneyFacade
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
        return $paymentMethodTransfer->getMethodName() === SharedHeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT;
    }

    /**
     * @param float $grandTotal
     *
     * @return bool
     */
    protected function isTotalOutOfRange(float $grandTotal): bool
    {
        $isOutOfRange = (
            $grandTotal < $this->config->getEasycreditCriteriaGrandTotalLessThan()
            || $grandTotal > $this->config->getEasycreditCriteriaGrandTotalMoreThan()
        );

        return $isOutOfRange;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isAddressCorrect(QuoteTransfer $quoteTransfer): bool
    {
        $isRejectedShippingAddress = strpos(
            $quoteTransfer->getShippingAddress()->getAddress1(),
            $this->config->getEasycreditCriteriaRejectedDeliveryAddress()
        );
        $isRejectedBillingAddress = strpos(
            $quoteTransfer->getBillingAddress()->getAddress1(),
            $this->config->getEasycreditCriteriaRejectedDeliveryAddress()
        );

        return ($isRejectedShippingAddress === false && $isRejectedBillingAddress === false);
    }
}
