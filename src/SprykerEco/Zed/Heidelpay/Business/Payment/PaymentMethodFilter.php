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
    protected const HEIDELPAY_PAYMENT_METHOD = 'heidelpay';
    protected const CONFIG_METHOD_PART_GET_CRIF = 'getCrif';
    protected const CONFIG_METHOD_PART_PAYMENT_METHODS = 'PaymentMethods';

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
        $availableMethods = $this->getAvailablePaymentMethods($quoteTransfer);

        $result = new ArrayObject();

        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethod) {
            if ($this->isPaymentMethodHeidelpay($paymentMethod) && !$this->isAvailable($paymentMethod, $availableMethods)) {
                continue;
            }

            $result->append($paymentMethod);
        }

        $paymentMethodsTransfer->setMethods($result);

        return $paymentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    protected function getAvailablePaymentMethods(QuoteTransfer $quoteTransfer): array
    {
        $method = static::CONFIG_METHOD_PART_GET_CRIF .
            ucfirst(strtolower($quoteTransfer->getHeidelpayCrif()->getResult())) .
            static::CONFIG_METHOD_PART_PAYMENT_METHODS;

        if (method_exists($this->config, $method)) {
            return $this->config->$method();
        }

        return $this->config->getCrifRedPaymentMethods();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param string[] $availableMethods
     *
     * @return bool
     */
    protected function isAvailable(PaymentMethodTransfer $paymentMethodTransfer, $availableMethods): bool
    {
        return in_array($paymentMethodTransfer->getMethodName(), $availableMethods);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return bool
     */
    protected function isPaymentMethodHeidelpay(PaymentMethodTransfer $paymentMethodTransfer): bool
    {
        return strpos($paymentMethodTransfer->getMethodName(), static::HEIDELPAY_PAYMENT_METHOD) !== false;
    }
}
