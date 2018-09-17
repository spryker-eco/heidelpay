<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Hydrator;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Client\Heidelpay\HeidelpayClientInterface;
use SprykerEco\Yves\Heidelpay\Hydrator\EasyCreditResponseToQuoteHydratorInterface;
use SprykerEco\Yves\Heidelpay\Handler\HeidelpayEasyCreditHandler;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;

class EasyCreditResponseToQuoteHydrator implements EasyCreditResponseToQuoteHydratorInterface
{
    /**
     * @var \SprykerEco\Yves\Heidelpay\Handler\HeidelpayEasyCreditHandler
     */
    private $heidelpayEasyCreditHandler;

    /**
     * @param \SprykerEco\Client\Heidelpay\HeidelpayClientInterface $heidelpayClient
     */
    public function __construct(HeidelpayEasyCreditHandler $heidelpayEasyCreditHandler)
    {
        $this->heidelpayEasyCreditHandler = $heidelpayEasyCreditHandler;
    }

    /**
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
