<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Handler;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Calculation\CalculationClientInterface;
use Spryker\Client\Quote\QuoteClientInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;

class HeidelpayCreditCardHandler extends HeidelpayHandler
{
    const PAYMENT_PROVIDER = HeidelpayConfig::PROVIDER_NAME;
    const CHECKOUT_PARTIAL_SUMMARY_PATH = 'Heidelpay/partial/summary';

    /**
     * @var \Spryker\Client\Calculation\CalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @var \Spryker\Client\Quote\QuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var array
     */
    protected static $paymentMethods = [
        HeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE => HeidelpayConfig::PAYMENT_METHOD_PAYPAL_AUTHORIZE,
        HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE => HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE,
        HeidelpayConfig::PAYMENT_METHOD_IDEAL => HeidelpayConfig::PAYMENT_METHOD_IDEAL,
        HeidelpayConfig::PAYMENT_METHOD_SOFORT => HeidelpayConfig::PAYMENT_METHOD_SOFORT,
    ];

    /**
     * @param \Spryker\Client\Calculation\CalculationClientInterface $calculationClient
     * @param \Spryker\Client\Quote\QuoteClientInterface $quoteClient
     */
    public function __construct(
        CalculationClientInterface $calculationClient,
        QuoteClientInterface $quoteClient
    ) {
        $this->calculationClient = $calculationClient;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer = parent::addPaymentToQuote($quoteTransfer);
        $this->addCurrentRegistrationToQuote($quoteTransfer);
        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);
        $this->quoteClient->setQuote($quoteTransfer);
        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addCurrentRegistrationToQuote(AbstractTransfer $quoteTransfer): void
    {
        $creditCardPayment = $quoteTransfer->getPayment()->getHeidelpayCreditCardSecure();
        $paymentOption = $creditCardPayment->getSelectedPaymentOption();

        if ($paymentOption === HeidelpayConfig::PAYMENT_OPTION_EXISTING_REGISTRATION) {
            $creditCardPayment->setSelectedRegistration(
                $creditCardPayment->getPaymentOptions()->getLastSuccessfulRegistration()
            );
        }
    }
}
