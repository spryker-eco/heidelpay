<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\CreditCard;

use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface RegistrationToQuoteHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer $registrationTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function hydrateCreditCardRegistrationToQuote(
        HeidelpayCreditCardRegistrationTransfer $registrationTransfer,
        QuoteTransfer $quoteTransfer
    ): void;
}
