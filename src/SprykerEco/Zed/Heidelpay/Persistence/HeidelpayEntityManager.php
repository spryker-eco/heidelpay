<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

use Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer;
use Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistrationQuery;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper\HeidelpayPersistenceMapper;

/**
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayPersistenceFactory getFactory()
 */
class HeidelpayEntityManager extends AbstractEntityManager implements HeidelpayEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayDirectDebitRegistrationTransfer
     */
    public function savePaymentHeidelpayDirectDebitRegistrationEntity(
        HeidelpayDirectDebitRegistrationTransfer $directDebitRegistrationTransfer
    ): HeidelpayDirectDebitRegistrationTransfer {
        $paymentHeidelpayDirectDebitRegistrationEntity = $this->getPaymentHeidelpayDirectDebitRegistrationQuery()
            ->filterByRegistrationUniqueId($directDebitRegistrationTransfer->getRegistrationUniqueId())
            ->findOneOrCreate();

        $paymentHeidelpayDirectDebitRegistrationEntity->fromArray(
            $directDebitRegistrationTransfer->getAccountInfo()->modifiedToArray()
        );
        $paymentHeidelpayDirectDebitRegistrationEntity
            ->setFkCustomerAddress($directDebitRegistrationTransfer->getIdCustomerAddress())
            ->setRegistrationUniqueId($directDebitRegistrationTransfer->getRegistrationUniqueId())
            ->setTransactionId($directDebitRegistrationTransfer->getTransactionId());

        $paymentHeidelpayDirectDebitRegistrationEntity->save();

        return $this->getMapper()
            ->mapEntityToHeidelpayDirectDebitRegistrationTransfer(
                $paymentHeidelpayDirectDebitRegistrationEntity,
                $directDebitRegistrationTransfer
            );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper\HeidelpayPersistenceMapper
     */
    protected function getMapper(): HeidelpayPersistenceMapper
    {
        return $this->getFactory()->createHeidelpayPersistenceMapper();
    }

    /**
     * @return \Orm\Zed\Heidelpay\Persistence\SpyPaymentHeidelpayDirectDebitRegistrationQuery
     */
    protected function getPaymentHeidelpayDirectDebitRegistrationQuery(): SpyPaymentHeidelpayDirectDebitRegistrationQuery
    {
        return $this->getFactory()->createPaymentHeidelpayDirectDebitRegistrationQuery();
    }
}
