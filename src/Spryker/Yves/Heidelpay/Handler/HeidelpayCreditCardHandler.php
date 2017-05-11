<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Heidelpay\Handler;

use Spryker\Shared\Heidelpay\HeidelpayConstants;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class HeidelpayCreditCardHandler extends HeidelpayHandler
{

    const PAYMENT_PROVIDER = HeidelpayConstants::PROVIDER_NAME;
    const CHECKOUT_PARTIAL_SUMMARY_PATH = 'Heidelpay/partial/summary';

    /**
     * @var array
     */
    protected static $paymentMethods = [
        HeidelpayConstants::PAYMENT_METHOD_PAYPAL_AUTHORIZE => HeidelpayConstants::PAYMENT_METHOD_PAYPAL_AUTHORIZE,
        HeidelpayConstants::PAYMENT_METHOD_CREDIT_CARD_SECURE => HeidelpayConstants::PAYMENT_METHOD_CREDIT_CARD_SECURE,
        HeidelpayConstants::PAYMENT_METHOD_IDEAL => HeidelpayConstants::PAYMENT_METHOD_IDEAL,
        HeidelpayConstants::PAYMENT_METHOD_SOFORT => HeidelpayConstants::PAYMENT_METHOD_SOFORT,
    ];

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function addPaymentToQuote(AbstractTransfer $quoteTransfer)
    {
        $quoteTransfer = parent::addPaymentToQuote($quoteTransfer);
        $this->addCurrentRegistrationToQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addCurrentRegistrationToQuote(AbstractTransfer $quoteTransfer)
    {
        $creditCardPayment = $quoteTransfer->getPayment()->getHeidelpayCreditCardSecure();
        $paymentOption = $creditCardPayment->getSelectedPaymentOption();

        if ($paymentOption === HeidelpayConstants::PAYMENT_OPTION_EXISTING_REGISTRATION) {
            $creditCardPayment->setSelectedRegistration(
                $creditCardPayment->getPaymentOptions()->getLastSuccessfulRegistration()
            );
        }
    }

}
