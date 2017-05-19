<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Heidelpay\Business\DataProviders\Customer;

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

trait CustomerTrait
{

    /**
     * @var array
     */
    private $uniqueCustomerEmailSlugs = [];

    /**
     * @var \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    private $johnDoeEntity;

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    public function createOrGetCustomerJohnDoe()
    {
        return $this->createCustomer('John', 'Doe');
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createCustomerJohnDoeTransfer()
    {
        $johnDoeEntity = $this->createOrGetCustomerJohnDoe();

        $johnDoeTransfer = (new CustomerTransfer())
            ->fromArray($johnDoeEntity->toArray(), true);

        return $johnDoeTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createCustomerJohnDoeGuestTransfer()
    {
        $johnDoeTransfer = $this->createCustomerJohnDoeTransfer();

        $johnDoeTransfer->setIsGuest(true)
            ->setIdCustomer(null);

        return $johnDoeTransfer;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    private function createCustomer($firstName, $lastName)
    {
        if ($this->johnDoeEntity === null) {
            $customer = (new SpyCustomer())
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail($this->getUniqueCustomerEmail($firstName . $lastName))
                ->setDateOfBirth('1970-01-01')
                ->setGender(SpyCustomerTableMap::COL_GENDER_MALE)
                ->setCustomerReference('heidelpay-test-' . $this->getUniqueCustomerEmail($firstName . $lastName));
            $customer->save();

            $this->johnDoeEntity = $customer;
        }

        return $this->johnDoeEntity;
    }

    /**
     * @param string $emailSlug
     *
     * @return string
     */
    private function getUniqueCustomerEmail($emailSlug)
    {
        if (!isset($this->uniqueCustomerEmailSlugs[$emailSlug])) {
            $this->uniqueCustomerEmailSlugs[$emailSlug] = uniqid($emailSlug) . '@test.com';
        }

        return $this->uniqueCustomerEmailSlugs[$emailSlug];
    }

}
