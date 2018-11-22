<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\Heidelpay;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Session\SessionClientFactoryTrait;
use Spryker\Client\ZedRequest\ZedRequestClientFactoryTrait;
use SprykerEco\Client\Heidelpay\Dependency\Client\HeidelpayToLocaleInterface;
use SprykerEco\Client\Heidelpay\Dependency\Client\HeidelpayToQuoteInterface;
use SprykerEco\Client\Heidelpay\Mapper\ApiResponseToRegistrationRequestTransfer;
use SprykerEco\Client\Heidelpay\Mapper\ApiResponseToRegistrationResponseTransferInterface;
use SprykerEco\Client\Heidelpay\Sdk\CreditCardRegistrationResponseParser;
use SprykerEco\Client\Heidelpay\Sdk\CreditCardRegistrationResponseParserInterface;
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
     * @return \SprykerEco\Client\Heidelpay\Dependency\Client\HeidelpayToLocaleInterface
     */
    public function getLocaleClient(): HeidelpayToLocaleInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \SprykerEco\Client\Heidelpay\Dependency\Client\HeidelpayToQuoteInterface
     */
    public function getQuoteClient(): HeidelpayToQuoteInterface
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
     * @return \SprykerEco\Client\Heidelpay\Mapper\ApiResponseToRegistrationResponseTransferInterface
     */
    public function createApiResponseToRegistrationResponseTransferMapper(): ApiResponseToRegistrationResponseTransferInterface
    {
        return new ApiResponseToRegistrationRequestTransfer();
    }
}
