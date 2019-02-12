<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Transaction;

use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Encoder\EncoderTrait;
use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConfig;

trait InitializeTransactionTrait
{
    use EncoderTrait;
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    public function createSuccessfulInitializeTransactionForQuote(SpySalesQuote $orderEntity)
    {
        $debitTransaction = new SpyPaymentHeidelpayTransactionLog();
        $debitTransaction
            ->setFkSalesOrder($orderEntity->getIdSalesOrder())
            ->setIdTransactionUnique('some unique id')
            ->setTransactionType(HeidelpayConfig::TRANSACTION_TYPE_DEBIT)
            ->setResponseCode('ACK')
            ->setRedirectUrl(HeidelpayTestConfig::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL)
            ->setRequestPayload('{}')
            ->setResponsePayload($this->encryptData(
                '{
                        "processing": {"result": "ACK"}, 
                        "payment": {"code": "CC.PA"}, 
                        "frontend": {"payment_frame_url": "' . HeidelpayTestConfig::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL . '"} 
                    }'
            ));

        $debitTransaction->save();
    }
}
