<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Hydrator;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface;
use SprykerEco\Yves\Heidelpay\Hydrator\Exception\EasyCreditResponseToQuoteHydratorException;

class EasyCreditResponseToQuoteHydrator implements EasyCreditResponseToQuoteHydratorInterface
{
    protected const PRICE_PRECISION = 100;

    /**
     * @var \SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface
     */
    protected $heidelpayEasyCreditHandler;

    /**
     * @param \SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface $heidelpayEasyCreditHandler
     */
    public function __construct(HeidelpayHandlerInterface $heidelpayEasyCreditHandler)
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
            ->setAccruingInterest(
                $this->convertDecimalToInteger($responseAsArray['CRITERION.EASYCREDIT_ACCRUINGINTEREST'])
            )
            ->setTotalAmount(
                $this->convertDecimalToInteger($responseAsArray['CRITERION.EASYCREDIT_TOTALAMOUNT'])
            );

        $quoteTransfer->setPayment($paymentTransfer);

        $this->heidelpayEasyCreditHandler->addPaymentToQuote($quoteTransfer);
    }

    /**
     * @param float $value
     *
     * @throws \SprykerEco\Yves\Heidelpay\Hydrator\Exception\EasyCreditResponseToQuoteHydratorException
     *
     * @return int
     */
    protected function convertDecimalToInteger(float $value): int
    {
        if (!is_float($value)) {
            throw new EasyCreditResponseToQuoteHydratorException(sprintf(
                'Only float values allowed for conversion to int. Current type is "%s"',
                gettype($value)
            ));
        }

        return (int)round($value * static::PRICE_PRECISION);
    }
}
