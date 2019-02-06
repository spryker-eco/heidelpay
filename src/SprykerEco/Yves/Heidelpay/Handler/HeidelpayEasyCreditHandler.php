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
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToPriceClientBridge;
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface;

class HeidelpayEasyCreditHandler extends HeidelpayHandler
{
    public const PAYMENT_PROVIDER = HeidelpayConfig::PROVIDER_NAME;
    public const EASYCREDIT_EXPENSE_TYPE = 'EASYCREDIT_EXPENSE_TYPE';
    public const EASYCREDIT_EXPENSE_NAME = 'EasyCredit';

    /**
     * @var \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToCalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @var \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToPriceClientBridge
     */
    protected $priceClient;

    /**
     * @param \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToCalculationClientInterface $calculationClient
     * @param \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToPriceClientBridge $priceClient
     * @param \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface $quoteClient
     */
    public function __construct(
        HeidelpayToCalculationClientInterface $calculationClient,
        HeidelpayToPriceClientBridge $priceClient,
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
        $this->replaceShipmentExpenseInQuote($quoteTransfer, $accruingInterestExpenseTransfer);

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
            ->setName(self::EASYCREDIT_EXPENSE_NAME)
            ->setType(self::EASYCREDIT_EXPENSE_TYPE)
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
    protected function setPrice(ExpenseTransfer $shipmentExpenseTransfer, $price, $priceMode)
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return void
     */
    protected function replaceShipmentExpenseInQuote(QuoteTransfer $quoteTransfer, ExpenseTransfer $expenseTransfer)
    {
        $otherExpenseCollection = new ArrayObject();
        foreach ($quoteTransfer->getExpenses() as $expense) {
            if ($expense->getType() !== self::EASYCREDIT_EXPENSE_TYPE) {
                $otherExpenseCollection->append($expense);
            }
        }

        $quoteTransfer->setExpenses($otherExpenseCollection);
        $quoteTransfer->addExpense($expenseTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransfer()
    {
        return new ExpenseTransfer();
    }
}
