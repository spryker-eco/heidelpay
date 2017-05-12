<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Heidelpay;

use Generated\Shared\Transfer\QuoteTransfer;

interface HeidelpayClientInterface
{

    /**
     * Specification:
     *  - Retrieves current locale;
     *  - Calls external library (heidelpay/php-customer-message) to get error message for customer, based on
     *    provided error code and locale.
     *
     * @api
     *
     * @param string $errorCode
     *
     * @return string
     */
    public function translateErrorMessageByCode($errorCode);

    /**
     * Specification:
     *  - Fetches from Zed transaction log for a given order by it's reference
     *
     * @api
     *
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    public function getAuthorizeTransactionLogForOrder($orderReference);

    /**
     * Specification:
     *  - For a given quote, requests from Zed list of allowed payment options for credit card payment method
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer
     */
    public function getCreditCardPaymentOptions(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Sends external response from payment provider (POST request) to Zed for processing
     *
     * @api
     *
     * @param array $externalResponse
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalPaymentResponse(array $externalResponse);

    /**
     * Specification:
     *  - Parses the external Heidelpay array response, transforming it to the transfer object
     *
     * @api
     *
     * @param array $externalResponse
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer
     */
    public function parseExternalResponse(array $externalResponse);

    /**
     * Specification:
     *  - Retrieve quote from current customer session
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuoteFromSession();

}
