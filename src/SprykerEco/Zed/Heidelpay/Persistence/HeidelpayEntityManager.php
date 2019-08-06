<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Persistence;

use Generated\Shared\Transfer\PaymentHeidelpayDirectDebitRegistrationTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper\HeidelpayPersistenceMapper;

/**
 * @method \SprykerEco\Zed\Heidelpay\Persistence\HeidelpayPersistenceFactory getFactory()
 */
class HeidelpayEntityManager extends AbstractEntityManager implements HeidelpayEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentHeidelpayDirectDebitRegistrationTransfer $paymentHeidelpayDirectDebitRegistrationTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentHeidelpayDirectDebitRegistrationTransfer
     */
    public function savePaymentHeidelpayDirectDebitRegistrationEntity(
        PaymentHeidelpayDirectDebitRegistrationTransfer $paymentHeidelpayDirectDebitRegistrationTransfer
    ): PaymentHeidelpayDirectDebitRegistrationTransfer {
        $paymentHeidelpayDirectDebitRegistrationEntity = $this->getFactory()
            ->createPaymentHeidelpayDirectDebitRegistrationQuery()
            ->filterByRegistrationUniqueId($paymentHeidelpayDirectDebitRegistrationTransfer->getRegistrationUniqueId())
            ->filterByFkCustomerAddress($paymentHeidelpayDirectDebitRegistrationTransfer->getIdCustomerAddress())
            ->findOneOrCreate();

        $paymentHeidelpayDirectDebitRegistrationEntity->fromArray(
            $paymentHeidelpayDirectDebitRegistrationTransfer->modifiedToArray()
        );
        $paymentHeidelpayDirectDebitRegistrationEntity->setFkCustomerAddress(
            $paymentHeidelpayDirectDebitRegistrationTransfer->getIdCustomerAddress()
        );

        $paymentHeidelpayDirectDebitRegistrationEntity->save();

        return $this->getMapper()
            ->mapEntityToHeidelpayDirectDebitRegistrationTransfer(
                $paymentHeidelpayDirectDebitRegistrationEntity,
                $paymentHeidelpayDirectDebitRegistrationTransfer
            );
    }

    /**
     * @return \SprykerEco\Zed\Heidelpay\Persistence\Propel\Mapper\HeidelpayPersistenceMapper
     */
    protected function getMapper(): HeidelpayPersistenceMapper
    {
        return $this->getFactory()->createHeidelpayPersistenceMapper();
    }
}
