<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Hydrator;

use Generated\Shared\Transfer\ExpenseTransfer;
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
    public function hydrateEasyCreditResponseToQuote(array $responseAsArray, QuoteTransfer $quoteTransfer): void
    {
        $paymentTransfer = $quoteTransfer->requirePayment()->getPayment();
        $paymentTransfer->setPaymentSelection(HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT);

        $paymentTransfer
            ->requireHeidelpayEasyCredit()
            ->getHeidelpayEasyCredit()
            ->setIdPaymentReference($responseAsArray['IDENTIFICATION.UNIQUEID'])
            ->setAmortisationText($responseAsArray['CRITERION.EASYCREDIT_AMORTISATIONTEXT'])
            ->setAccruingInterest($responseAsArray['CRITERION.EASYCREDIT_ACCRUINGINTEREST'])
            ->setTotalAmount($responseAsArray['CRITERION.EASYCREDIT_TOTALAMOUNT']);

        $quoteTransfer->setPayment($paymentTransfer);
        $easyCreditExpensePrice = $responseAsArray['CRITERION.EASYCREDIT_MONTHLYRATEAMOUNT'];
        $easyCreditExpenseTransfer = (new ExpenseTransfer())
            ->setName('EasyCredit')
            ->setType('EXPENSE_TYPE')
            ->setUnitPrice($easyCreditExpensePrice)
            ->setSumPrice($easyCreditExpensePrice)
            ->setUnitPriceToPayAggregation($easyCreditExpensePrice)
            ->setSumPriceToPayAggregation($easyCreditExpensePrice)
            ->setUnitNetPrice($easyCreditExpensePrice)
            ->setSumNetPrice($easyCreditExpensePrice);

        $quoteTransfer->addExpense($easyCreditExpenseTransfer);

        $this->heidelpayEasyCreditHandler->addPaymentToQuote($quoteTransfer);
    }
}