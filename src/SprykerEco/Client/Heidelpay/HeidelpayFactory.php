<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Heidelpay;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Session\SessionClientFactoryTrait;
use Spryker\Client\ZedRequest\ZedRequestClientFactoryTrait;
use SprykerEco\Client\Heidelpay\Mapper\ApiResponseToRegistrationResponseTransfer;
use SprykerEco\Client\Heidelpay\Sdk\CreditCardRegistrationResponseParser;
use SprykerEco\Client\Heidelpay\Sdk\HeidelpayApiAdapter;
use SprykerEco\Client\Heidelpay\Zed\HeidelpayStub;

/**
 * @method \SprykerEco\Client\Heidelpay\HeidelpayConfig getConfig()
 */
class HeidelpayFactory extends AbstractFactory
{

    use SessionClientFactoryTrait;
    use ZedRequestClientFactoryTrait;

    /**
     * @return \SprykerEco\Client\Heidelpay\Sdk\HeidelpayApiAdapter
     */
    public function createHeidelpayApiAdapter()
    {
        return new HeidelpayApiAdapter();
    }

    /**
     * @return \SprykerEco\Client\Heidelpay\Dependency\Client\HeidelpayToLocaleInterface
     */
    public function getLocaleClient()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \SprykerEco\Client\Heidelpay\Dependency\Client\HeidelpayToQuoteInterface
     */
    public function getQuoteClient()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerEco\Client\Heidelpay\Zed\HeidelpayStubInterface
     */
    public function createZedStub()
    {
        return new HeidelpayStub($this->getZedRequestClient());
    }

    /**
     * @return \SprykerEco\Client\Heidelpay\Sdk\CreditCardRegistrationResponseParserInterface
     */
    public function createExternalResponseValidator()
    {
        return new CreditCardRegistrationResponseParser(
            $this->createApiResponseToRegistrationResponseTransferMapper(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Client\Heidelpay\Mapper\ApiResponseToRegistrationResponseTransferInterface
     */
    protected function createApiResponseToRegistrationResponseTransferMapper()
    {
        return new ApiResponseToRegistrationResponseTransfer();
    }

}
