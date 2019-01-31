<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPreSavePaymentInterface;

class EasyCredit extends BaseHeidelpayPaymentMethod implements PaymentWithPreSavePaymentInterface
{
    /**
     * @param \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay $paymentEntity
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function addDataToPayment(SpyPaymentHeidelpay $paymentEntity, QuoteTransfer $quoteTransfer): void
    {
        $paymentReference = $this->getPaymentReferenceFromQuote($quoteTransfer);
        $paymentEntity->setIdPaymentReference($paymentReference);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getPaymentReferenceFromQuote(QuoteTransfer $quoteTransfer): string
    {
        return $quoteTransfer
            ->getPayment()
            ->getHeidelpayEasyCredit()
            ->getIdPaymentReference();
    }
}
