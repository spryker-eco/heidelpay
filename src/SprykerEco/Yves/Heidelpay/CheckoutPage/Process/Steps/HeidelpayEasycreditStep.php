<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\CheckoutPage\Process\Steps;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithExternalRedirectInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AbstractBaseStep;
use Symfony\Component\HttpFoundation\Request;

class HeidelpayEasycreditStep extends AbstractBaseStep implements StepWithExternalRedirectInterface
{
    /**
     * @var string
     */
    protected $externalRedirectUrl;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        if ($this->isEasyCreditPaymentMethod($quoteTransfer) === false) {
            return $quoteTransfer;
        }

        $this->externalRedirectUrl = $quoteTransfer->getHeidelpayPayment()->getExternalRedirectUrl();

        return $quoteTransfer;
    }

    /**
     * @return string
     */
    public function getExternalRedirectUrl()
    {
        return $this->externalRedirectUrl;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isEasyCreditPaymentMethod(AbstractTransfer $quoteTransfer): bool
    {
        $result = ($quoteTransfer->getPayment() !== null
            && $quoteTransfer->getPayment()->getPaymentSelection() === HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT);

        return $result;
    }
}
