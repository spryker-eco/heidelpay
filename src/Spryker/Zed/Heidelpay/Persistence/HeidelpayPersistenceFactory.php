<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heidelpay\Persistence;

use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistrationQuery;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayQuery;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Heidelpay\HeidelpayConfig getConfig()
 * @method \Spryker\Zed\Heidelpay\Persistence\HeidelpayQueryContainer getQueryContainer()
 */
class HeidelpayPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayQuery
     */
    public function createPaymentHeidelpayQuery()
    {
        return SpyPaymentHeidelpayQuery::create();
    }

    /**
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function createPaymentHeidelpayTransactionLogQuery()
    {
        return SpyPaymentHeidelpayTransactionLogQuery::create();
    }

    /**
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistrationQuery
     */
    public function createHeidelpayCreditCardRegistrationQuery()
    {
        return SpyPaymentHeidelpayCreditCardRegistrationQuery::create();
    }

}
