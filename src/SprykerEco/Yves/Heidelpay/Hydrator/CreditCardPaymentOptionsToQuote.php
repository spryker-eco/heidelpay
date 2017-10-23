<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Hydrator;

use Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Client\Heidelpay\HeidelpayClientInterface;

class CreditCardPaymentOptionsToQuote implements CreditCardPaymentOptionsToQuoteInterface
{
    /**
     * @var \SprykerEco\Client\Heidelpay\HeidelpayClientInterface
     */
    private $heidelpayClient;

    /**
     * @param \SprykerEco\Client\Heidelpay\HeidelpayClientInterface $heidelpayClient
     */
    public function __construct(HeidelpayClientInterface $heidelpayClient)
    {
        $this->heidelpayClient = $heidelpayClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function hydrate(QuoteTransfer $quoteTransfer)
    {
        if (!$this->hasQuoteCreditCardPayment($quoteTransfer)) {
            $this->initCreditCardPayment($quoteTransfer);
        }

        $this->hydrateCreditCardPaymentOptions($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return boolean
     */
    protected function hasQuoteCreditCardPayment(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getPayment()->getHeidelpayCreditCardSecure() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function hydrateCreditCardPaymentOptions(QuoteTransfer $quoteTransfer)
    {
        $creditCardPaymentOptionsTransfer = $this->heidelpayClient->getCreditCardPaymentOptions($quoteTransfer);

        $quotePayment = $quoteTransfer->getPayment()->getHeidelpayCreditCardSecure();
        $quotePayment->setPaymentOptions($creditCardPaymentOptionsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function initCreditCardPayment(QuoteTransfer $quoteTransfer)
    {
        $creditCardPaymentTransfer = (new HeidelpayCreditCardPaymentTransfer())
            ->setPaymentOptions(new HeidelpayCreditCardPaymentOptionsTransfer());

        $quoteTransfer->getPayment()->setHeidelpayCreditCardSecure($creditCardPaymentTransfer);
    }
}
