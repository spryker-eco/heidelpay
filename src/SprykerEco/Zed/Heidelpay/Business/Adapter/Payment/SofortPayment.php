<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Heidelpay\PhpPaymentApi\PaymentMethods\SofortPaymentMethod;

class SofortPayment extends BasePayment implements SofortPaymentInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorize(HeidelpayRequestTransfer $authorizeRequestTransfer): HeidelpayResponseTransfer
    {
        $sofortMethod = new SofortPaymentMethod();
        $this->prepareRequest($authorizeRequestTransfer, $sofortMethod->getRequest());
        $sofortMethod->authorize();

        return $this->verifyAndParseResponse($sofortMethod->getResponse());
    }
}
