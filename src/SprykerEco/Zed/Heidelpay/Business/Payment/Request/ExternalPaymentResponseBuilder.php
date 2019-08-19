<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Request;

use Generated\Shared\Transfer\HeidelpayExternalPaymentResponseTransfer;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReaderInterface;

class ExternalPaymentResponseBuilder implements ExternalPaymentResponseBuilderInterface
{
    public const REQUEST_PARAM_ORDER_ID = 'IDENTIFICATION_TRANSACTIONID';

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReaderInterface
     */
    protected $paymentReader;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReaderInterface $paymentReader
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
    public function buildExternalResponseTransfer(array $postRequestParams): HeidelpayExternalPaymentResponseTransfer
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
    protected function getPaymentMethodForOrder(int $idSalesOrder): string
    {
        $paymentTransfer = $this->paymentReader->getPaymentByIdSalesOrder($idSalesOrder);

        return $paymentTransfer->getPaymentMethod();
    }
}
