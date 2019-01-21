<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment;

use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpay;
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
    public function updatePaymentReferenceByIdSalesOrder(string $paymentReference, int $idSalesOrder): void
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
    protected function getPaymentEntityByIdSalesOrder(int $idSalesOrder): SpyPaymentHeidelpay
    {
        $heidelpayPaymentEntity = $this->heidelpayQueryContainer
            ->queryPaymentByIdSalesOrder($idSalesOrder)
            ->findOne();

        return $heidelpayPaymentEntity;
    }
}
