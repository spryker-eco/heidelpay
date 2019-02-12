<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Handler;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToCalculationClientInterface;
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToPriceClientInterface;
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface;

class HeidelpayEasyCreditHandler extends HeidelpayHandler
{
    public const PAYMENT_PROVIDER = HeidelpayConfig::PROVIDER_NAME;
    protected const EASYCREDIT_EXPENSE_TYPE = 'EASYCREDIT_EXPENSE_TYPE';
    protected const EASYCREDIT_EXPENSE_NAME = 'EasyCredit';

    /**
     * @var \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToCalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @var \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @param \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToCalculationClientInterface $calculationClient
     * @param \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToPriceClientInterface $priceClient
     * @param \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface $quoteClient
     */
    public function __construct(
        HeidelpayToCalculationClientInterface $calculationClient,
        HeidelpayToPriceClientInterface $priceClient,
        HeidelpayToQuoteClientInterface $quoteClient
    ) {
        $this->calculationClient = $calculationClient;
        $this->priceClient = $priceClient;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $paymentTransfer = $quoteTransfer->getPayment();

        $accruingInterestExpenseTransfer = $this->createAccruingInterestExpense(
            $paymentTransfer,
            $quoteTransfer->getPriceMode()
        );
        $this->replaceAccruingInterestExpenseTransfer($quoteTransfer, $accruingInterestExpenseTransfer);

        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);
        $this->quoteClient->setQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param string|null $priceMode
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer;
     */
    protected function createAccruingInterestExpense(PaymentTransfer $paymentTransfer, $priceMode): ExpenseTransfer
    {
        $easyCreditExpensePrice = $paymentTransfer
            ->getHeidelpayEasyCredit()
            ->getAccruingInterest();

        $expenseTransfer = $this
            ->createExpenseTransfer()
            ->setName(static::EASYCREDIT_EXPENSE_NAME)
            ->setType(static::EASYCREDIT_EXPENSE_TYPE)
            ->setQuantity(1)
            ->setSumTaxAmount(0);

        $this->setPrice(
            $expenseTransfer,
            $easyCreditExpensePrice,
            $priceMode
        );

        return $expenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shipmentExpenseTransfer
     * @param int $price
     * @param string $priceMode
     *
     * @return void
     */
    protected function setPrice(ExpenseTransfer $shipmentExpenseTransfer, $price, $priceMode): void
    {
        if ($priceMode === $this->priceClient->getNetPriceModeIdentifier()) {
            $shipmentExpenseTransfer->setUnitGrossPrice(0);
            $shipmentExpenseTransfer->setSumGrossPrice(0);
            $shipmentExpenseTransfer->setUnitNetPrice($price);
            return;
        }

        $shipmentExpenseTransfer->setUnitNetPrice(0);
        $shipmentExpenseTransfer->setSumNetPrice(0);
        $shipmentExpenseTransfer->setUnitGrossPrice($price);
    }

    /**
     * Make sure we add accruing interest expense only once.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return void
     */
    protected function replaceAccruingInterestExpenseTransfer(
        QuoteTransfer $quoteTransfer,
        ExpenseTransfer $expenseTransfer
    ): void {
        $otherExpenseCollection = new ArrayObject();
        foreach ($quoteTransfer->getExpenses() as $expense) {
            if ($expense->getType() !== static::EASYCREDIT_EXPENSE_TYPE) {
                $otherExpenseCollection->append($expense);
            }
        }

        $quoteTransfer->setExpenses($otherExpenseCollection);
        $quoteTransfer->addExpense($expenseTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransfer(): ExpenseTransfer
    {
        return new ExpenseTransfer();
    }
}
