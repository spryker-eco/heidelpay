<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Hydrator;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Yves\Heidelpay\Dependency\Plugin\HeidelpayToMoneyPluginInterface;
use SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface;

class EasyCreditResponseToQuoteHydrator implements EasyCreditResponseToQuoteHydratorInterface
{
    /**
     * @var \SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface
     */
    protected $heidelpayEasyCreditHandler;

    /**
     * @var \SprykerEco\Yves\Heidelpay\Dependency\Plugin\HeidelpayToMoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @param \SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface $heidelpayEasyCreditHandler
     * @param \SprykerEco\Yves\Heidelpay\Dependency\Plugin\HeidelpayToMoneyPluginInterface $moneyPlugin
     */
    public function __construct(
        HeidelpayHandlerInterface $heidelpayEasyCreditHandler,
        HeidelpayToMoneyPluginInterface $moneyPlugin
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
            ->setIdPaymentReference($responseAsArray['IDENTIFICATION_UNIQUEID'])
            ->setAmortisationText($responseAsArray['CRITERION.EASYCREDIT_AMORTISATIONTEXT'])
            ->setAccruingInterest(
                $this->moneyPlugin->convertDecimalToInteger((float)$responseAsArray['CRITERION.EASYCREDIT_ACCRUINGINTEREST'])
            )
            ->setTotalAmount(
                $this->moneyPlugin->convertDecimalToInteger((float)$responseAsArray['CRITERION.EASYCREDIT_TOTALAMOUNT'])
            );

        $quoteTransfer->setPayment($paymentTransfer);

        $this->heidelpayEasyCreditHandler->addPaymentToQuote($quoteTransfer);
    }
}
