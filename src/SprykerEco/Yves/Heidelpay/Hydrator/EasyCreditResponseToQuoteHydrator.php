<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Hydrator;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Yves\Heidelpay\Handler\HeidelpayEasyCreditHandler;

class EasyCreditResponseToQuoteHydrator implements EasyCreditResponseToQuoteHydratorInterface
{
    /**
     * @var \SprykerEco\Yves\Heidelpay\Handler\HeidelpayEasyCreditHandler
     */
    private $heidelpayEasyCreditHandler;

    /**
     * @param \SprykerEco\Yves\Heidelpay\Handler\HeidelpayEasyCreditHandler $heidelpayEasyCreditHandler
     */
    public function __construct(HeidelpayEasyCreditHandler $heidelpayEasyCreditHandler)
    {
        $this->heidelpayEasyCreditHandler = $heidelpayEasyCreditHandler;
    }

    /**
     * @param array $responseAsArray
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function hydrateEasyCreditResponseToQuote(array $responseAsArray, QuoteTransfer $quoteTransfer)
    {
        $paymentTransfer = $quoteTransfer->requirePayment()->getPayment();
        $paymentTransfer->setPaymentSelection(HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT);

        $paymentTransfer
            ->requireHeidelpayEasyCredit()
            ->getHeidelpayEasyCredit()
            ->setAmortisationText($responseAsArray['CRITERION_EASYCREDIT_AMORTISATIONTEXT'])
            ->setaccruingInterest($responseAsArray['CRITERION_EASYCREDIT_ACCRUINGINTEREST'])
            ->setTotalAmount($responseAsArray['CRITERION_EASYCREDIT_TOTALAMOUNT']);

        $quoteTransfer->setPayment($paymentTransfer);

        $this->heidelpayEasyCreditHandler->addPaymentToQuote($quoteTransfer);
    }
}
