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

        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethod) {
            if ($this->isPaymentMethodHeidelpayEasyCredit($paymentMethod) && !$this->isQuoteValidForEasyCredit($quoteTransfer)) {
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteValidForEasyCredit(QuoteTransfer $quoteTransfer): bool
    {
        return in_array($quoteTransfer->getShippingAddress()->getIso2Code(), $this->config->getEasycreditCriteriaCountryIsoCodes(), true)
            && $this->isAddressCorrect($quoteTransfer)
            && $quoteTransfer->getBillingAddress()->toArray() === $quoteTransfer->getShippingAddress()->toArray()
            && !$this->isQuoteGrandTotalOutOfRange($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteGrandTotalOutOfRange(QuoteTransfer $quoteTransfer): bool
    {
        $grandTotal = $this->moneyFacade->convertIntegerToDecimal($quoteTransfer->getTotals()->getGrandTotal());

        return (
            $grandTotal < $this->config->getEasycreditCriteriaGrandTotalLessThan()
            || $grandTotal > $this->config->getEasycreditCriteriaGrandTotalMoreThan()
        );
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
