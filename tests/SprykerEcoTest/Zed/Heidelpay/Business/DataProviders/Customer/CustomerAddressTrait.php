<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer;

use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\Base\SpyCustomer;
use Orm\Zed\Customer\Persistence\SpyCustomerAddress;

trait CustomerAddressTrait
{

    /**
     * @param \Orm\Zed\Customer\Persistence\Base\SpyCustomer $customer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddress
     */
    public function createCustomerAddressByCustomer(SpyCustomer $customer)
    {
        return $this->createAddress($customer);
    }

    /**
     *
     * @param \Orm\Zed\Customer\Persistence\Base\SpyCustomer $customer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddress
     */
    private function createAddress(SpyCustomer $customer)
    {
        $country = SpyCountryQuery::create()->findOneByIso2Code('DE');

        $customerAddress = (new SpyCustomerAddress())
            ->setFkCustomer($customer->getIdCustomer())
            ->setFkCountry($country->getIdCountry())
            ->setFirstName($customer->getFirstName())
            ->setLastName($customer->getLastName())
            ->setAddress1('Straße des 17. Juni 135')
            ->setCity('Berlin')
            ->setZipCode('10623');
        $customerAddress->save();

        return $customerAddress;
    }

}
