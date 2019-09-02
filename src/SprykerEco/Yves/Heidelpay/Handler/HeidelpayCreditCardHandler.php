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
    /**
     * @var \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToCalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @var \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface
     */
    protected $quoteClient;

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
    public function addPaymentToQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
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
