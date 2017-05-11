<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Heidelpay;

use Spryker\Client\Heidelpay\Mapper\ApiResponseToRegistrationResponseTransfer;
use Spryker\Client\Heidelpay\Sdk\CreditCardRegistrationResponseParser;
use Spryker\Client\Heidelpay\Sdk\HeidelpayApiAdapter;
use Spryker\Client\Heidelpay\Zed\HeidelpayStub;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Session\SessionClientFactoryTrait;
use Spryker\Client\ZedRequest\ZedRequestClientFactoryTrait;

/**
 * @method \Spryker\Client\Heidelpay\HeidelpayConfig getConfig()
 */
class HeidelpayFactory extends AbstractFactory
{

    use SessionClientFactoryTrait;
    use ZedRequestClientFactoryTrait;

    /**
     * @return \Spryker\Client\Heidelpay\Sdk\HeidelpayApiAdapter
     */
    public function createHeidelpayApiAdapter()
    {
        return new HeidelpayApiAdapter();
    }

    /**
     * @return \Spryker\Client\Heidelpay\Dependency\Client\HeidelpayToLocaleInterface
     */
    public function getLocaleClient()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \Spryker\Client\Heidelpay\Dependency\Client\HeidelpayToQuoteInterface
     */
    public function getQuoteClient()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\Heidelpay\Zed\HeidelpayStubInterface
     */
    public function createZedStub()
    {
        return new HeidelpayStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\Heidelpay\Sdk\CreditCardRegistrationResponseParserInterface
     */
    public function createExternalResponseValidator()
    {
        return new CreditCardRegistrationResponseParser(
            $this->createApiResponseToRegistrationResponseTransferMapper(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Client\Heidelpay\Mapper\ApiResponseToRegistrationResponseTransferInterface
     */
    protected function createApiResponseToRegistrationResponseTransferMapper()
    {
        return new ApiResponseToRegistrationResponseTransfer();
    }

}
