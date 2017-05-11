<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heidelpay\Business\Payment\Request;

use Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer;
use Spryker\Zed\Heidelpay\Business\Payment\PaymentReaderInterface;

class ExternalPaymentResponseBuilder implements ExternalPaymentResponseBuilderInterface
{

    const REQUEST_PARAM_ORDER_ID = 'IDENTIFICATION_TRANSACTIONID';

    /**
     * @var \Spryker\Zed\Heidelpay\Business\Payment\PaymentReaderInterface
     */
    protected $paymentReader;

    /**
     * @param \Spryker\Zed\Heidelpay\Business\Payment\PaymentReaderInterface $paymentReader
     */
    public function __construct(PaymentReaderInterface $paymentReader)
    {
        $this->paymentReader = $paymentReader;
    }

    /**
     * @param array $postRequestParams
     *
     * @return \Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer
     */
    public function buildExternalResponseTransfer(array $postRequestParams)
    {
        $idSalesOrder = $postRequestParams[static::REQUEST_PARAM_ORDER_ID];

        $externalResponseTransfer = (new HeidelpayExternalPaymentResponseTransfer())
            ->setBody($postRequestParams)
            ->setIdSalesOrder($idSalesOrder)
            ->setPaymentMethod(
                $this->getPaymentMethodForOrder($idSalesOrder)
            );

        return $externalResponseTransfer;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string
     */
    protected function getPaymentMethodForOrder($idSalesOrder)
    {
        $paymentTransfer = $this->paymentReader->getPaymentByIdSalesOrder($idSalesOrder);

        return $paymentTransfer->getPaymentMethod();
    }

}
