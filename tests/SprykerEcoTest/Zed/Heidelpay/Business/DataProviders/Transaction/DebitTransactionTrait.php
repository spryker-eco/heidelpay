<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Transaction;

use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Encoder\EncoderTrait;
use SprykerEcoTest\Zed\Heidelpay\Business\HeidelpayTestConstants;

trait DebitTransactionTrait
{

    use EncoderTrait;
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    public function createSuccessfulDebitTransactionForOrder(SpySalesOrder $orderEntity)
    {
        $debitTransaction = new SpyPaymentHeidelpayTransactionLog();
        $debitTransaction
            ->setFkSalesOrder($orderEntity->getIdSalesOrder())
            ->setIdTransactionUnique('some unique id')
            ->setTransactionType(HeidelpayConfig::TRANSACTION_TYPE_DEBIT)
            ->setResponseCode('ACK')
            ->setRedirectUrl(HeidelpayTestConstants::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL)
            ->setRequestPayload('{}')
            ->setResponsePayload($this->encryptData(
                    '{
                        "processing": {"result": "ACK"}, 
                        "payment": {"code": "CC.PA"}, 
                        "frontend": {"payment_frame_url": "' . HeidelpayTestConstants::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL . '"} 
                    }'
                )
            );

        $debitTransaction->save();
    }

}
