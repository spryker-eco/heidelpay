<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Type;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;

interface PaymentWithAuthorizeOnRegistrationInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorizeOnRegistration(HeidelpayRequestTransfer $authorizeRequestTransfer);
}
