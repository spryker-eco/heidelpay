<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithPostSaveOrderInterface;

class EasyCredit extends BaseHeidelpayPaymentMethod implements PaymentWithPostSaveOrderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function postSaveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $redirectUrl = $this->getCheckoutRedirectUrlFromAuthorizeOnRegistrationTransactionLog(
            $checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder()
        );

        $this->setExternalRedirect($redirectUrl, $checkoutResponseTransfer);
    }
}
