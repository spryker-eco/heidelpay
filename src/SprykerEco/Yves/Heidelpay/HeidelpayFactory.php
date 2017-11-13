<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerEco\Yves\Heidelpay\CreditCard\RegistrationToQuoteHydrator;
use SprykerEco\Yves\Heidelpay\Form\CreditCardSecureSubForm;
use SprykerEco\Yves\Heidelpay\Form\DataProvider\CreditCardSecureDataProvider;
use SprykerEco\Yves\Heidelpay\Form\DataProvider\IdealDataProvider;
use SprykerEco\Yves\Heidelpay\Form\DataProvider\PaypalAuthorizeDataProvider;
use SprykerEco\Yves\Heidelpay\Form\DataProvider\PaypalDebitDataProvider;
use SprykerEco\Yves\Heidelpay\Form\DataProvider\SofortDataProvider;
use SprykerEco\Yves\Heidelpay\Form\IdealSubForm;
use SprykerEco\Yves\Heidelpay\Form\PaypalAuthorizeSubForm;
use SprykerEco\Yves\Heidelpay\Form\PaypalDebitSubForm;
use SprykerEco\Yves\Heidelpay\Form\SofortSubForm;
use SprykerEco\Yves\Heidelpay\Handler\HeidelpayCreditCardHandler;
use SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandler;
use SprykerEco\Yves\Heidelpay\Handler\PaymentFailureHandler;
use SprykerEco\Yves\Heidelpay\Hydrator\CreditCardPaymentOptionsToQuote;
use SprykerEco\Yves\Heidelpay\Mapper\HeidelpayResponseToIdealAuthorizeForm;

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayConfig getConfig()
 */
class HeidelpayFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface
     */
    public function createHeidelpayHandler()
    {
        return new HeidelpayHandler();
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface
     */
    public function createHeidelpayCreditCardHandler()
    {
        return new HeidelpayCreditCardHandler();
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\Handler\PaymentFailureHandlerInterface
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
     * @return \SprykerEco\Client\Heidelpay\HeidelpayClientInterface
     */
    public function getHeidelpayClient()
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::CLIENT_HEIDELPAY);
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\Mapper\HeidelpayResponseToIdealAuthorizeFormInterface
     */
    public function createHeidelpayResponseToIdealAuthorizeFormMapper()
    {
        return new HeidelpayResponseToIdealAuthorizeForm();
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\CreditCard\RegistrationToQuoteHydratorInterface
     */
    public function createCreditCardRegistrationToQuoteHydrator()
    {
        return new RegistrationToQuoteHydrator(
            $this->createHeidelpayHandler()
        );
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\HeidelpayConfigInterface
     */
    public function getYvesConfig()
    {
        return $this->getConfig();
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\Hydrator\CreditCardPaymentOptionsToQuoteInterface
     */
    protected function createCreditCardPaymentOptionsToQuoteHydrator()
    {
        return new CreditCardPaymentOptionsToQuote(
            $this->getHeidelpayClient()
        );
    }
}
