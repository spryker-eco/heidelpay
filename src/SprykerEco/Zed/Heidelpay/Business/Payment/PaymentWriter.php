<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment;

use SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface;

class PaymentWriter implements PaymentWriterInterface
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
     * @param string $paymentReference
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function updatePaymentReferenceByIdSalesOrder($paymentReference, $idSalesOrder)
    {
        $heidelpayPaymentEntity = $this->getPaymentEntityByIdSalesOrder($idSalesOrder);

        $heidelpayPaymentEntity
            ->setIdPaymentReference($paymentReference)
            ->save();
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
