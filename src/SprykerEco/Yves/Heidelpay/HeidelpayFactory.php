<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay;

use Spryker\Yves\Heidelpay\CreditCard\RegistrationResponseHandler;
use Spryker\Yves\Heidelpay\Form\CreditCardSecureSubForm;
use Spryker\Yves\Heidelpay\Form\DataProvider\CreditCardSecureDataProvider;
use Spryker\Yves\Heidelpay\Form\DataProvider\IdealDataProvider;
use Spryker\Yves\Heidelpay\Form\DataProvider\PaypalAuthorizeDataProvider;
use Spryker\Yves\Heidelpay\Form\DataProvider\PaypalDebitDataProvider;
use Spryker\Yves\Heidelpay\Form\DataProvider\SofortDataProvider;
use Spryker\Yves\Heidelpay\Form\IdealSubForm;
use Spryker\Yves\Heidelpay\Form\PaypalAuthorizeSubForm;
use Spryker\Yves\Heidelpay\Form\PaypalDebitSubForm;
use Spryker\Yves\Heidelpay\Form\SofortSubForm;
use Spryker\Yves\Heidelpay\Handler\HeidelpayCreditCardHandler;
use Spryker\Yves\Heidelpay\Handler\HeidelpayHandler;
use Spryker\Yves\Heidelpay\Handler\PaymentFailureHandler;
use Spryker\Yves\Heidelpay\Hydrator\CreditCardPaymentOptionsToQuote;
use Spryker\Yves\Heidelpay\Mapper\HeidelpayResponseToIdealAuthorizeForm;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \Spryker\Yves\Heidelpay\HeidelpayConfig getConfig()
 */
class HeidelpayFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\Heidelpay\Handler\HeidelpayHandlerInterface
     */
    public function createHeidelpayHandler()
    {
        return new HeidelpayHandler(
            $this->getHeidelpayClient()
        );
    }

    /**
     * @return \Spryker\Yves\Heidelpay\Handler\HeidelpayHandlerInterface
     */
    public function createHeidelpayCreditCardHandler()
    {
        return new HeidelpayCreditCardHandler(
            $this->getHeidelpayClient()
        );
    }

    /**
     * @return \Spryker\Yves\Heidelpay\Handler\PaymentFailureHandlerInterface
     */
    public function createPaymentFailureHandler()
    {
        return new PaymentFailureHandler(
            $this->getHeidelpayClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createSofortForm()
    {
        return new SofortSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createIdealForm()
    {
        return new IdealSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createCreditCardSecureForm()
    {
        return new CreditCardSecureSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createPaypalAuthorizeForm()
    {
        return new PaypalAuthorizeSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createPaypalDebitForm()
    {
        return new PaypalDebitSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSofortFormDataProvider()
    {
        return new SofortDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createIdealFormDataProvider()
    {
        return new IdealDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createCreditCardSecureFormDataProvider()
    {
        return new CreditCardSecureDataProvider(
            $this->createCreditCardPaymentOptionsToQuoteHydrator()
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPaypalAuthorizeFormDataProvider()
    {
        return new PaypalAuthorizeDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPaypalDebitFormDataProvider()
    {
        return new PaypalDebitDataProvider();
    }

    /**
     * @return \Spryker\Yves\Heidelpay\Hydrator\CreditCardPaymentOptionsToQuoteInterface
     */
    protected function createCreditCardPaymentOptionsToQuoteHydrator()
    {
        return new CreditCardPaymentOptionsToQuote(
            $this->getHeidelpayClient()
        );
    }

    /**
     * @return \Spryker\Client\Heidelpay\HeidelpayClientInterface
     */
    public function getHeidelpayClient()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::CLIENT_HEIDELPAY);
    }

    /**
     * @return \Spryker\Yves\Heidelpay\Mapper\HeidelpayResponseToIdealAuthorizeFormInterface
     */
    public function createHeidelpayResponseToIdealAuthorizeFormMapper()
    {
        return new HeidelpayResponseToIdealAuthorizeForm();
    }

    /**
     * @return \Spryker\Yves\Heidelpay\CreditCard\RegistrationResponseHandlerInterface
     */
    public function createRegistrationResponseHandler()
    {
        return new RegistrationResponseHandler(
            $this->getHeidelpayClient(),
            $this->createHeidelpayHandler()
        );
    }

    /**
     * @return \Spryker\Yves\Heidelpay\HeidelpayConfig
     */
    public function getYvesConfig()
    {
        return $this->getConfig();
    }

}
