<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer;

use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\SpyCustomerAddress;

trait CustomerAddressTrait
{

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddress
     */
    public function createCustomerAddressJohnDoe()
    {
        return $this->createAddress('John', 'Doe');
    }

    /**
     * @param string $firstName
     * @param string $lastName
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddress
     */
    private function createAddress($firstName, $lastName)
    {
        $country = SpyCountryQuery::create()->findOneByIso2Code('DE');

        $customerAddress = (new SpyCustomerAddress())
            ->setFkCountry($country->getIdCountry())
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setAddress1('Straße des 17. Juni 135')
            ->setCity('Berlin')
            ->setZipCode('10623');
        $customerAddress->save();

        return $customerAddress;
    }

}
