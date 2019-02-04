<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay;

use Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
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
    public function translateErrorMessageByCode(string $errorCode): string;

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
    public function getAuthorizeTransactionLogForOrder(string $orderReference): HeidelpayTransactionLogTransfer;

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
    public function getCreditCardPaymentOptions(QuoteTransfer $quoteTransfer): HeidelpayCreditCardPaymentOptionsTransfer;

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
    public function processExternalPaymentResponse(array $externalResponse): HeidelpayPaymentProcessingResponseTransfer;

    /**
     * Specification:
     *  - Sends external response from payment provider (POST request) to Zed for processing. Specific for Easy Credit
     *
     * @api
     *
     * @param array $externalResponse
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processExternalEasyCreditPaymentResponse(array $externalResponse);

    /**
     * Specification:
     *  - Sends credit card registration request to Zed for saving
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer
     */
    public function saveCreditCardRegistration(HeidelpayRegistrationRequestTransfer $registrationRequestTransfer): HeidelpayRegistrationSaveResponseTransfer;

    /**
     * Specification:
     *  - tries to find credit card registration by registration id and customer quote to reassure,
     *  that credit card registration really belongs to current customer
     *
     * @api
     *
     * @param int $idRegistration
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer|null
     */
    public function findRegistrationByIdAndQuote(int $idRegistration, QuoteTransfer $quoteTransfer): ?HeidelpayCreditCardRegistrationTransfer;

    /**
     * Specification:
     *  - Parses the external Heidelpay array response, transforming it to the transfer object
     *
     * @api
     *
     * @param array $externalResponse
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer
     */
    public function parseExternalResponse(array $externalResponse): HeidelpayRegistrationRequestTransfer;

    /**
     * Specification:
     *  - Retrieve quote from current customer session
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuoteFromSession(): QuoteTransfer;

    /**
     * Specification:
     *  - Filter response from heidelpay from unnecessary params.
     *
     * @api
     *
     * @param array $responseArray
     *
     * @return array
     */
    public function filterResponseParameters(array $responseArray);

    /**
     * Specification:
     *  - Send payment Initialization request (HP.INI)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function heidelpayEasycreditRequest(QuoteTransfer $quoteTransfer): HeidelpayResponseTransfer;
}
