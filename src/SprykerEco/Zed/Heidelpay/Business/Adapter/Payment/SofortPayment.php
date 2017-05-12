<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Payment;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Heidelpay\PhpApi\PaymentMethods\SofortPaymentMethod;
use Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithAuthorizeInterface;
use Spryker\Zed\Heidelpay\Business\Payment\Type\PaymentWithExternalResponseInterface;

class SofortPayment extends BasePayment implements SofortPaymentInterface, PaymentWithAuthorizeInterface, PaymentWithExternalResponseInterface
{

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $authorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function authorize(HeidelpayRequestTransfer $authorizeRequestTransfer)
    {
        $sofortMethod = new SofortPaymentMethod();
        $this->prepareRequest($authorizeRequestTransfer, $sofortMethod->getRequest());
        $sofortMethod->authorize();

        return $this->verifyAndParseResponse($sofortMethod->getResponse());
    }

}
