<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\CreditCard;

use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;
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
    ) {

        $paymentTransfer = $quoteTransfer->requirePayment()->getPayment();
        $paymentTransfer->setPaymentSelection(HeidelpayConstants::PAYMENT_METHOD_CREDIT_CARD_SECURE);

        $this->heidelpayPaymentHandler->addPaymentToQuote($quoteTransfer);

        $paymentTransfer
            ->requireHeidelpayCreditCardSecure()
            ->getHeidelpayCreditCardSecure()
            ->setSelectedRegistration($registrationTransfer)
            ->setSelectedPaymentOption(HeidelpayConstants::PAYMENT_OPTION_NEW_REGISTRATION);
    }

}
