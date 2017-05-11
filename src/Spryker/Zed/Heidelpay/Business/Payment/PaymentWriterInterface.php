<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heidelpay\Business\Payment;

interface PaymentWriterInterface
{

    /**
     * @param string $paymentReference
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function updatePaymentReferenceByIdSalesOrder($paymentReference, $idSalesOrder);

}
