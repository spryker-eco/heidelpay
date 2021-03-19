<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Hydrator;

use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface;

class RegistrationToQuoteHydrator implements RegistrationToQuoteHydratorInterface
{
    /**
     * @var \SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface
     */
    private $heidelpayPaymentHandler;

    /**
     * @param \SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface $heidelpayPaymentHandler
     */
    public function __construct(HeidelpayHandlerInterface $heidelpayPaymentHandler)
    {
        $this->heidelpayPaymentHandler = $heidelpayPaymentHandler;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer $registrationTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function hydrateCreditCardRegistrationToQuote(
        HeidelpayCreditCardRegistrationTransfer $registrationTransfer,
        QuoteTransfer $quoteTransfer
    ): void {
        $paymentTransfer = $quoteTransfer->requirePayment()->getPayment();
        $paymentTransfer->setPaymentSelection(HeidelpayConfig::PAYMENT_METHOD_CREDIT_CARD_SECURE);

        $paymentTransfer
            ->requireHeidelpayCreditCardSecure()
            ->getHeidelpayCreditCardSecure()
            ->setSelectedRegistration($registrationTransfer)
            ->setSelectedPaymentOption(HeidelpayConfig::PAYMENT_OPTION_NEW_REGISTRATION);

        $quoteTransfer->setPayment($paymentTransfer);

        $this->heidelpayPaymentHandler->addPaymentToQuote($quoteTransfer);
    }
}
