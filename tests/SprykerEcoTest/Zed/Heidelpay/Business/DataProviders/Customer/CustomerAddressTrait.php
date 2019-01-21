<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
    public function createCustomerAddressByCustomer(SpyCustomer $customer): SpyCustomerAddress
    {
        return $this->createAddress($customer);
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\Base\SpyCustomer $customer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddress
     */
    private function createAddress(SpyCustomer $customer): SpyCustomerAddress
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
