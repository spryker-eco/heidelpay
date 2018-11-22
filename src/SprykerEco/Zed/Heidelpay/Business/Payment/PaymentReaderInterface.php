<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment;

use Generated\Shared\Transfer\HeidelpayPaymentTransfer;

interface PaymentReaderInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentTransfer
     */
    public function getPaymentByIdSalesOrder(int $idSalesOrder): HeidelpayPaymentTransfer;

    /**
     * @param int $idSalesOrder
     *
     * @return string|null
     */
    public function getIdBasketByIdSalesOrder(int $idSalesOrder): ?string;
}
