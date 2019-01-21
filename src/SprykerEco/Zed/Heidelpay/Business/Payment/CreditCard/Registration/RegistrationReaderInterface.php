<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\CreditCard\Registration;

use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface RegistrationReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer
     */
    public function getLastSuccessfulRegistrationForQuote(QuoteTransfer $quoteTransfer): HeidelpayCreditCardRegistrationTransfer;

    /**
     * @param int $idRegistration
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer
     */
    public function findCreditCardRegistrationByIdAndQuote(int $idRegistration, QuoteTransfer $quoteTransfer): HeidelpayCreditCardRegistrationTransfer;
}
