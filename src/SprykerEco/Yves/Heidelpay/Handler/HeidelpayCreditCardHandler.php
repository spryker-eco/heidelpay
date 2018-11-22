<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Handler;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToCalculationClientInterface;
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface;

class HeidelpayCreditCardHandler extends HeidelpayHandler
{
    public const PAYMENT_PROVIDER = HeidelpayConfig::PROVIDER_NAME;
    public const CHECKOUT_PARTIAL_SUMMARY_PATH = 'Heidelpay/partial/summary';

    /**
     * @var \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToCalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @var \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface
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
     * @param \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToCalculationClientInterface $calculationClient
     * @param \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface $quoteClient
     */
    public function __construct(
        HeidelpayToCalculationClientInterface $calculationClient,
        HeidelpayToQuoteClientInterface $quoteClient
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
