<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistrationQuery;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistrationQuery;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayQuery;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper\HeidelpayPersistenceMapper;

/**
 * @method \SprykerEco\Zed\Heidelpay\HeidelpayConfig getConfig()
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayQueryContainerInterface getQueryContainer()
 */
class HeidelpayPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayQuery
     */
    public function createPaymentHeidelpayQuery(): SpyPaymentHeidelpayQuery
    {
        return SpyPaymentHeidelpayQuery::create();
    }

    /**
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayTransactionLogQuery
     */
    public function createPaymentHeidelpayTransactionLogQuery(): SpyPaymentHeidelpayTransactionLogQuery
    {
        return SpyPaymentHeidelpayTransactionLogQuery::create();
    }

    /**
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayCreditCardRegistrationQuery
     */
    public function createHeidelpayCreditCardRegistrationQuery(): SpyPaymentHeidelpayCreditCardRegistrationQuery
    {
        return SpyPaymentHeidelpayCreditCardRegistrationQuery::create();
    }

    /**
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistrationQuery
     */
    public function createPaymentHeidelpayDirectDebitRegistrationQuery(): SpyPaymentHeidelpayDirectDebitRegistrationQuery
    {
        return SpyPaymentHeidelpayDirectDebitRegistrationQuery::create();
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper\HeidelpayPersistenceMapper
     */
    public function createHeidelpayPersistenceMapper(): HeidelpayPersistenceMapper
    {
        return new HeidelpayPersistenceMapper();
    }
}
