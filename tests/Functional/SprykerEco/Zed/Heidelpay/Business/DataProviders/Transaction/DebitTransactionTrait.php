<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Heidelpay\Business\DataProviders\Transaction;

use Functional\SprykerEco\Zed\Heidelpay\Business\HeidelpayTestConstants;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLog;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Payolution\Persistence\Map\SpyPaymentPayolutionTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerEco\Shared\Heidelpay\HeidelpayConstants;

trait DebitTransactionTrait
{

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
            ->setTransactionType(HeidelpayConstants::TRANSACTION_TYPE_DEBIT)
            ->setResponseCode('ACK')
            ->setRedirectUrl(HeidelpayTestConstants::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL)
            ->setRequestPayload('{}')
            ->setResponsePayload(
                '{
                    "processing": {"result": "ACK"}, 
                    "payment": {"code": "CC.PA"}, 
                    "frontend": {"payment_frame_url": "' . HeidelpayTestConstants::CHECKOUT_EXTERNAL_SUCCESS_REDIRECT_URL . '"} 
                }'
            );

        $debitTransaction->save();
    }

}
