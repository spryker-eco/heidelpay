<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomer;

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
    public function createOrGetCustomerJohnDoe(): SpyCustomer
    {
        return $this->createCustomer('John', 'Doe');
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    public function createOrGetCustomerByQuote(QuoteTransfer $quoteTransfer): SpyCustomer
    {
        return $this->createCustomer(
            $quoteTransfer->getCustomer()->getFirstName(),
            $quoteTransfer->getCustomer()->getLastName(),
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createCustomerJohnDoeTransfer(): CustomerTransfer
    {
        $johnDoeEntity = $this->createOrGetCustomerJohnDoe();

        $johnDoeTransfer = (new CustomerTransfer())
            ->fromArray($johnDoeEntity->toArray(), true);

        return $johnDoeTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createCustomerJohnDoeGuestTransfer(): CustomerTransfer
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
    private function createCustomer(string $firstName, string $lastName): SpyCustomer
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
    private function getUniqueCustomerEmail(string $emailSlug): string
    {
        if (!isset($this->uniqueCustomerEmailSlugs[$emailSlug])) {
            $this->uniqueCustomerEmailSlugs[$emailSlug] = uniqid($emailSlug) . '@test.com';
        }

        return $this->uniqueCustomerEmailSlugs[$emailSlug];
    }
}
