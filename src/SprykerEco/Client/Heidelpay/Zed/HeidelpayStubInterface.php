<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Heidelpay\Zed;

use Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer;
use Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer;
use Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer;
use Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer;
use Generated\Shared\Transfer\HeidelpayTransactionLogTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface HeidelpayStubInterface
{
    /**
     * @param string $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\HeidelpayTransactionLogTransfer
     */
    public function getAuthorizeTransactionLogByOrderReference(string $idSalesOrder): HeidelpayTransactionLogTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardPaymentOptionsTransfer
     */
    public function getCreditCardPaymentOptions(QuoteTransfer $quoteTransfer): HeidelpayCreditCardPaymentOptionsTransfer;

    /**
     * @param \Generated\Shared\Transfer\HeidelpayExternalPaymentRequestTransfer $externalPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function processExternalPaymentResponse(
        HeidelpayExternalPaymentRequestTransfer $externalPaymentRequestTransfer
    );

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayCreditCardRegistrationTransfer|null
     */
    public function findCreditCardRegistrationByIdAndQuote(
        HeidelpayRegistrationByIdAndQuoteRequestTransfer $findRegistrationRequestTransfer
    ): ?HeidelpayCreditCardRegistrationTransfer;

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationSaveResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function saveCreditCardRegistration(
        HeidelpayRegistrationRequestTransfer $registrationRequestTransfer
    );
}
