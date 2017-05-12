<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Request;

interface ExternalPaymentResponseBuilderInterface
{

    /**
     * @param array $postRequestParams
     *
     * @return \Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer
     */
    public function buildExternalResponseTransfer(array $postRequestParams);

}
