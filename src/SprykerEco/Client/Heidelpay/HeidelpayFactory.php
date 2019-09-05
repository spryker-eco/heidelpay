<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Session\SessionClientFactoryTrait;
use Spryker\Client\ZedRequest\ZedRequestClientFactoryTrait;
use SprykerEco\Client\Heidelpay\Dependency\Client\HeidelpayToLocaleClientInterface;
use SprykerEco\Client\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface;
use SprykerEco\Client\Heidelpay\Mapper\ApiResponseToRegistrationRequestTransfer;
use SprykerEco\Client\Heidelpay\Mapper\ApiResponseToRegistrationResponseTransferInterface;
use SprykerEco\Client\Heidelpay\Mapper\DirectDebitRegistrationResponseMapper;
use SprykerEco\Client\Heidelpay\Mapper\DirectDebitRegistrationResponseMapperInterface;
use SprykerEco\Client\Heidelpay\Sdk\CreditCardRegistrationResponseParser;
use SprykerEco\Client\Heidelpay\Sdk\CreditCardRegistrationResponseParserInterface;
use SprykerEco\Client\Heidelpay\Sdk\DirectDebitRegistrationResponseParser;
use SprykerEco\Client\Heidelpay\Sdk\DirectDebitRegistrationResponseParserInterface;
use SprykerEco\Client\Heidelpay\Sdk\HeidelpayApiAdapter;
use SprykerEco\Client\Heidelpay\Sdk\HeidelpayApiAdapterInterface;
use SprykerEco\Client\Heidelpay\Zed\HeidelpayStub;
use SprykerEco\Client\Heidelpay\Zed\HeidelpayStubInterface;

/**
 * @method \SprykerEco\Client\Heidelpay\HeidelpayConfig getConfig()
 */
class HeidelpayFactory extends AbstractFactory
{
    use SessionClientFactoryTrait;
    use ZedRequestClientFactoryTrait;

    /**
     * @return \SprykerEco\Client\Heidelpay\Sdk\HeidelpayApiAdapterInterface
     */
    public function createHeidelpayApiAdapter(): HeidelpayApiAdapterInterface
    {
        return new HeidelpayApiAdapter();
    }

    /**
     * @return \SprykerEco\Client\Heidelpay\Dependency\Client\HeidelpayToLocaleClientInterface
     */
    public function getLocaleClient(): HeidelpayToLocaleClientInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \SprykerEco\Client\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface
     */
    public function getQuoteClient(): HeidelpayToQuoteClientInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerEco\Client\Heidelpay\Zed\HeidelpayStubInterface
     */
    public function createZedStub(): HeidelpayStubInterface
    {
        return new HeidelpayStub($this->getZedRequestClient());
    }

    /**
     * @return \SprykerEco\Client\Heidelpay\Sdk\CreditCardRegistrationResponseParserInterface
     */
    public function createExternalResponseValidator(): CreditCardRegistrationResponseParserInterface
    {
        return new CreditCardRegistrationResponseParser(
            $this->createApiResponseToRegistrationResponseTransferMapper(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Client\Heidelpay\Sdk\DirectDebitRegistrationResponseParserInterface
     */
    public function createDirectDebitRegistrationResponseParser(): DirectDebitRegistrationResponseParserInterface
    {
        return new DirectDebitRegistrationResponseParser(
            $this->createDirectDebitRegistrationResponseMapper(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Client\Heidelpay\Mapper\ApiResponseToRegistrationResponseTransferInterface
     */
    public function createApiResponseToRegistrationResponseTransferMapper(): ApiResponseToRegistrationResponseTransferInterface
    {
        return new ApiResponseToRegistrationRequestTransfer();
    }

    /**
     * @return \SprykerEco\Client\Heidelpay\Mapper\DirectDebitRegistrationResponseMapperInterface
     */
    public function createDirectDebitRegistrationResponseMapper(): DirectDebitRegistrationResponseMapperInterface
    {
        return new DirectDebitRegistrationResponseMapper();
    }
}
