<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Hydrator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface;

class EasyCreditResponseToQuoteHydrator implements EasyCreditResponseToQuoteHydratorInterface
{
    protected const EASYCREDIT_IDENTIFICATION_UNIQUE_ID = 'IDENTIFICATION_UNIQUEID';
    protected const EASYCREDIT_AMORTISATION_TEXT = 'CRITERION_EASYCREDIT_AMORTISATIONTEXT';
    protected const EASYCREDIT_PRECONTRACT_INFORMATION_URL = 'CRITERION_EASYCREDIT_PRECONTRACTINFORMATIONURL';
    protected const EASYCREDIT_ACCRUING_INTEREST = 'CRITERION_EASYCREDIT_ACCRUINGINTEREST';
    protected const EASYCREDIT_TOTAL_AMOUNT = 'CRITERION_EASYCREDIT_TOTALAMOUNT';
    protected const EASYCREDIT_TOTAL_ORDER_AMOUNT = 'CRITERION_EASYCREDIT_TOTALORDERAMOUNT';

    /**
     * @var \SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface
     */
    protected $heidelpayEasyCreditHandler;

    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @param \SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface $heidelpayEasyCreditHandler
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     */
    public function __construct(
        HeidelpayHandlerInterface $heidelpayEasyCreditHandler,
        MoneyPluginInterface $moneyPlugin
    ) {
        $this->heidelpayEasyCreditHandler = $heidelpayEasyCreditHandler;
        $this->moneyPlugin = $moneyPlugin;
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
            ->setIdPaymentReference($responseAsArray[static::EASYCREDIT_IDENTIFICATION_UNIQUE_ID])
            ->setAmortisationText($responseAsArray[static::EASYCREDIT_AMORTISATION_TEXT])
            ->setPreContractionInformationUrl($responseAsArray[static::EASYCREDIT_PRECONTRACT_INFORMATION_URL])
            ->setAccruingInterest(
                $this->moneyPlugin->convertDecimalToInteger((float)$responseAsArray[static::EASYCREDIT_ACCRUING_INTEREST])
            )
            ->setTotalAmount(
                $this->moneyPlugin->convertDecimalToInteger((float)$responseAsArray[static::EASYCREDIT_TOTAL_AMOUNT])
            )
            ->setTotalOrderAmount(
                $this->moneyPlugin->convertDecimalToInteger((float)$responseAsArray[static::EASYCREDIT_TOTAL_ORDER_AMOUNT])
            );

        $quoteTransfer->setPayment($paymentTransfer);

        $this->heidelpayEasyCreditHandler->addPaymentToQuote($quoteTransfer);
    }
}
