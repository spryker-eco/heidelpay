<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Processor;

use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Symfony\Component\HttpFoundation\Request;

interface HeidelpayDirectDebitRegistrationProcessorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function processNewRegistration(Request $request): HeidelpayDirectDebitRegistrationTransfer;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function processSuccessRegistration(Request $request): HeidelpayDirectDebitRegistrationTransfer;
}
