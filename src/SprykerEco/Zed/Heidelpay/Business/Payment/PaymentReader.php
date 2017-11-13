<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment;

use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface;

class PaymentReader implements PaymentReaderInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface
     */
    protected $heidelpayQueryContainer;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface $heidelpayQueryContainer
     */
    public function __construct(HeidelpayQueryContainerInterface $heidelpayQueryContainer)
    {
        $this->heidelpayQueryContainer = $heidelpayQueryContainer;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\HeidelpayPaymentTransfer
     */
    public function getPaymentByIdSalesOrder($idSalesOrder)
    {
        $heidelpayPaymentEntity = $this->getPaymentEntityByIdSalesOrder($idSalesOrder);

        $paymentTransfer = new HeidelpayPaymentTransfer();
        $paymentTransfer->fromArray($heidelpayPaymentEntity->toArray(), true);

        return $paymentTransfer;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay
     */
    protected function getPaymentEntityByIdSalesOrder($idSalesOrder)
    {
        $heidelpayPaymentEntity = $this->heidelpayQueryContainer
            ->queryPaymentByIdSalesOrder($idSalesOrder)
            ->findOne();

        return $heidelpayPaymentEntity;
    }
}
