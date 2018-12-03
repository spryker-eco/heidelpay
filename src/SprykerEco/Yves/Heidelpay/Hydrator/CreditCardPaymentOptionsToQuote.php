<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
    public function hydrate(QuoteTransfer $quoteTransfer): void
    {
        if (!$this->hasQuoteCreditCardPayment($quoteTransfer)) {
            $this->initCreditCardPayment($quoteTransfer);
        }

        $this->hydrateCreditCardPaymentOptions($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasQuoteCreditCardPayment(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getPayment()->getHeidelpayCreditCardSecure() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function hydrateCreditCardPaymentOptions(QuoteTransfer $quoteTransfer): void
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
    protected function initCreditCardPayment(QuoteTransfer $quoteTransfer): void
    {
        $creditCardPaymentTransfer = (new HeidelpayCreditCardPaymentTransfer())
            ->setPaymentOptions(new HeidelpayCreditCardPaymentOptionsTransfer());

        $quoteTransfer->getPayment()->setHeidelpayCreditCardSecure($creditCardPaymentTransfer);
    }
}
