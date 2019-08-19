<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Processor;

use Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer;
use Symfony\Component\HttpFoundation\Request;

interface HeidelpayPaymentResponseProcessorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentProcessingResponseTransfer
     */
    public function processPaymentResponse(Request $request): HeidelpayPaymentProcessingResponseTransfer;
}
