<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Order;

use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;

trait OrderAddressTrait
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    public function createOrderAddressJohnDoe(): SpySalesOrderAddress
    {
        return $this->createAddress('John', 'Doe');
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    public function createOrderAddressByQoute($quoteTransfer): SpySalesOrderAddress
    {
        return $this->createAddress(
            $quoteTransfer->getCustomer()->getFirstName(),
            $quoteTransfer->getCustomer()->getLastName(),
        );
    }

    /**
     * @param string $firstName
     * @param string $lastName
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    private function createAddress(string $firstName, string $lastName): SpySalesOrderAddress
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
