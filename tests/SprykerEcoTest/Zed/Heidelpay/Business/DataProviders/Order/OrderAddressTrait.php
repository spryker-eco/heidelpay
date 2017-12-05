<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order;

use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;

trait OrderAddressTrait
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    public function createOrderAddressJohnDoe()
    {
        return $this->createAddress('John', 'Doe');
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    public function createOrderAddressByQoute($quoteTransfer)
    {
        return $this->createAddress(
            $quoteTransfer->getCustomer()->getFirstName(),
            $quoteTransfer->getCustomer()->getLastName()
        );
    }

    /**
     * @param string $firstName
     * @param string $lastName
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    private function createAddress($firstName, $lastName)
    {
        $country = SpyCountryQuery::create()->findOneByIso2Code('DE');

        $billingAddress = (new SpySalesOrderAddress())
            ->setFkCountry($country->getIdCountry())
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setAddress1('Straße des 17. Juni 135')
            ->setCity('Berlin')
            ->setZipCode('10623');
        $billingAddress->save();

        return $billingAddress;
    }
}
