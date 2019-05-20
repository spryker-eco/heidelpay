<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Request;

use Generated\Shared\Transfer\HeidelpayAsyncTransfer;
use Generated\Shared\Transfer\HeidelpayRequestTransfer;

class EasyCreditAdapterRequestFromOrderBuilder extends AdapterRequestFromOrderBuilder
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function hydrateAsyncParameters(HeidelpayRequestTransfer $heidelpayRequestTransfer): HeidelpayRequestTransfer
    {
        $asyncTransfer = (new HeidelpayAsyncTransfer())
            ->setLanguageCode($this->config->getAsyncLanguageCode())
            ->setResponseUrl($this->config->getEasyCreditPaymentResponseUrl());

        $heidelpayRequestTransfer->setAsync($asyncTransfer);

        return $heidelpayRequestTransfer;
    }
}
