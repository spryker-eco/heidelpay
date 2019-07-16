<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay;

use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToCalculationClientInterface;
use SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface;
use SprykerEco\Yves\Heidelpay\Form\CreditCardSecureSubForm;
use SprykerEco\Yves\Heidelpay\Form\DataProvider\CreditCardSecureDataProvider;
use SprykerEco\Yves\Heidelpay\Form\DataProvider\EasyCreditDataProvider;
use SprykerEco\Yves\Heidelpay\Form\DataProvider\IdealDataProvider;
use SprykerEco\Yves\Heidelpay\Form\DataProvider\InvoiceSecuredB2CDataProvider;
use SprykerEco\Yves\Heidelpay\Form\DataProvider\PaypalAuthorizeDataProvider;
use SprykerEco\Yves\Heidelpay\Form\DataProvider\PaypalDebitDataProvider;
use SprykerEco\Yves\Heidelpay\Form\DataProvider\SofortDataProvider;
use SprykerEco\Yves\Heidelpay\Form\EasyCreditSubForm;
use SprykerEco\Yves\Heidelpay\Form\IdealSubForm;
use SprykerEco\Yves\Heidelpay\Form\InvoiceSecuredB2CSubForm;
use SprykerEco\Yves\Heidelpay\Form\PaypalAuthorizeSubForm;
use SprykerEco\Yves\Heidelpay\Form\PaypalDebitSubForm;
use SprykerEco\Yves\Heidelpay\Form\SofortSubForm;
use SprykerEco\Yves\Heidelpay\Handler\HeidelpayCreditCardHandler;
use SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandler;
use SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface;
use SprykerEco\Yves\Heidelpay\Handler\PaymentFailureHandler;
use SprykerEco\Yves\Heidelpay\Handler\PaymentFailureHandlerInterface;
use SprykerEco\Yves\Heidelpay\Hydrator\CreditCardPaymentOptionsToQuote;
use SprykerEco\Yves\Heidelpay\Hydrator\CreditCardPaymentOptionsToQuoteInterface;
use SprykerEco\Yves\Heidelpay\Hydrator\EasyCreditResponseToQuoteHydrator;
use SprykerEco\Yves\Heidelpay\Hydrator\EasyCreditResponseToQuoteHydratorInterface;
use SprykerEco\Yves\Heidelpay\Hydrator\RegistrationToQuoteHydrator;
use SprykerEco\Yves\Heidelpay\Hydrator\RegistrationToQuoteHydratorInterface;
use SprykerEco\Yves\Heidelpay\Mapper\EasyCreditResponseToGetParametersMapper;
use SprykerEco\Yves\Heidelpay\Mapper\EasyCreditResponseToGetParametersMapperInterface;
use SprykerEco\Yves\Heidelpay\Mapper\HeidelpayResponseToIdealAuthorizeForm;
use SprykerEco\Yves\Heidelpay\Mapper\HeidelpayResponseToIdealAuthorizeFormInterface;
use SprykerEco\Yves\Heidelpay\Processor\Notification\HeidelpayNotificationProcessor;
use SprykerEco\Yves\Heidelpay\Processor\Notification\HeidelpayNotificationProcessorInterface;
use SprykerEco\Yves\Heidelpay\Processor\Notification\Mapper\HeidelpayNotificationProcessorMapper;
use SprykerEco\Yves\Heidelpay\Processor\Notification\Mapper\HeidelpayNotificationProcessorMapperInterface;

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayConfig getConfig()
 * @method \SprykerEco\Client\Heidelpay\HeidelpayClientInterface getClient()
 */
class HeidelpayFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface
     */
    public function createHeidelpayHandler(): HeidelpayHandlerInterface
    {
        return new HeidelpayHandler();
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\Handler\HeidelpayHandlerInterface
     */
    public function createHeidelpayCreditCardHandler(): HeidelpayHandlerInterface
    {
        return new HeidelpayCreditCardHandler(
            $this->getCalculationClient(),
            $this->getQuoteClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\Handler\PaymentFailureHandlerInterface
     */
    public function createPaymentFailureHandler(): PaymentFailureHandlerInterface
    {
        return new PaymentFailureHandler(
            $this->getClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createSofortForm(): SubFormInterface
    {
        return new SofortSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createIdealForm(): SubFormInterface
    {
        return new IdealSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createCreditCardSecureForm(): SubFormInterface
    {
        return new CreditCardSecureSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createEasyCreditForm(): SubFormInterface
    {
        return new EasyCreditSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createPaypalAuthorizeForm(): SubFormInterface
    {
        return new PaypalAuthorizeSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createPaypalDebitForm(): SubFormInterface
    {
        return new PaypalDebitSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createInvoiceSecuredB2CSubForm(): SubFormInterface
    {
        return new InvoiceSecuredB2CSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSofortFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new SofortDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createIdealFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new IdealDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createCreditCardSecureFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new CreditCardSecureDataProvider(
            $this->createCreditCardPaymentOptionsToQuoteHydrator()
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPaypalAuthorizeFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new PaypalAuthorizeDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPaypalDebitFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new PaypalDebitDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createEasyCreditFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new EasyCreditDataProvider($this->getConfig());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createInvoiceSecuredB2CDataProvider(): StepEngineFormDataProviderInterface
    {
        return new InvoiceSecuredB2CDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\Mapper\HeidelpayResponseToIdealAuthorizeFormInterface
     */
    public function createHeidelpayResponseToIdealAuthorizeFormMapper(): HeidelpayResponseToIdealAuthorizeFormInterface
    {
        return new HeidelpayResponseToIdealAuthorizeForm();
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\Hydrator\RegistrationToQuoteHydratorInterface
     */
    public function createCreditCardRegistrationToQuoteHydrator(): RegistrationToQuoteHydratorInterface
    {
        return new RegistrationToQuoteHydrator(
            $this->createHeidelpayCreditCardHandler()
        );
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\Hydrator\EasyCreditResponseToQuoteHydratorInterface
     */
    public function createEasyCreditResponseToQuoteHydrator(): EasyCreditResponseToQuoteHydratorInterface
    {
        return new EasyCreditResponseToQuoteHydrator($this->getMoneyPlugin());
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\Mapper\EasyCreditResponseToGetParametersMapperInterface
     */
    public function createEasyCreditResponseToGetParametersMapper(): EasyCreditResponseToGetParametersMapperInterface
    {
        return new EasyCreditResponseToGetParametersMapper();
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\Hydrator\CreditCardPaymentOptionsToQuoteInterface
     */
    public function createCreditCardPaymentOptionsToQuoteHydrator(): CreditCardPaymentOptionsToQuoteInterface
    {
        return new CreditCardPaymentOptionsToQuote($this->getClient());
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\Processor\Notification\HeidelpayNotificationProcessorInterface
     */
    public function createHeidelpayNotificationProcessor(): HeidelpayNotificationProcessorInterface
    {
        return new HeidelpayNotificationProcessor(
            $this->createHeidelpayNotificationProcessorMapper(),
            $this->getClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\Processor\Notification\Mapper\HeidelpayNotificationProcessorMapperInterface
     */
    public function createHeidelpayNotificationProcessorMapper(): HeidelpayNotificationProcessorMapperInterface
    {
        return new HeidelpayNotificationProcessorMapper();
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToCalculationClientInterface
     */
    public function getCalculationClient(): HeidelpayToCalculationClientInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    public function getMoneyPlugin(): MoneyPluginInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::PLUGIN_MONEY);
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\Dependency\Client\HeidelpayToQuoteClientInterface
     */
    public function getQuoteClient(): HeidelpayToQuoteClientInterface
    {
        return $this->getProvidedDependency(HeidelpayDependencyProvider::CLIENT_QUOTE);
    }
}
