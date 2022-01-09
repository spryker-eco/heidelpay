<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class HeidelpayRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    protected const HEIDELPAY_BUNDLE_NAME = 'Heidelpay';

    /**
     * @var string
     */
    public const ROUTE_NAME_HEIDELPAY_PAYMENT = 'heidelpay-payment';

    /**
     * @var string
     */
    public const ROUTE_NAME_HEIDELPAY_PAYMENT_FAILED = 'heidelpay-payment-failed';

    /**
     * @var string
     */
    public const ROUTE_NAME_HEIDELPAY_EASY_CREDIT_PAYMENT = 'heidelpay-easy-credit-payment';

    /**
     * @var string
     */
    public const ROUTE_NAME_HEIDELPAY_EASY_CREDIT_INITIALIZE_PAYMENT = 'heidelpay-easy-credit-initialize-payment';

    /**
     * @var string
     */
    public const ROUTE_NAME_HEIDELPAY_IDEAL_AUTHORIZE = 'heidelpay-ideal-authorize';

    /**
     * @var string
     */
    public const ROUTE_NAME_HEIDELPAY_CREDIT_CARD_REGISTER = 'heidelpay-cc-register';

    /**
     * @var string
     */
    public const ROUTE_NAME_HEIDELPAY_CREDIT_CARD_REGISTER_SUCCESS = 'heidelpay-cc-register-success';

    /**
     * @var string
     */
    public const ROUTE_NAME_HEIDELPAY_DIRECT_DEBIT_REGISTER = 'heidelpay-dd-register';

    /**
     * @var string
     */
    public const ROUTE_NAME_HEIDELPAY_DIRECT_DEBIT_REGISTER_SUCCESS = 'heidelpay-dd-register-success';

    /**
     * @var string
     */
    public const ROUTE_NAME_HEIDELPAY_NOTIFICATION = 'heidelpay-notification';

    /**
     * {@inheritDoc}
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addHeidelpayPaymentRoute($routeCollection);
        $routeCollection = $this->addHeidelpayPaymentFailedRoute($routeCollection);
        $routeCollection = $this->addHeidelpayEasyCreditPaymentRoute($routeCollection);
        $routeCollection = $this->addHeidelpayEasyCreditInitializePaymentRoute($routeCollection);
        $routeCollection = $this->addHeidelpayIdealAuthorizeRoute($routeCollection);
        $routeCollection = $this->addHeidelpayCreditCardRegisterRoute($routeCollection);
        $routeCollection = $this->addHeidelpayCreditCardRegisterSuccessRoute($routeCollection);
        $routeCollection = $this->addHeidelpayDirectDebitRegisterRoute($routeCollection);
        $routeCollection = $this->addHeidelpayDirectDebitRegisterSuccessRoute($routeCollection);
        $routeCollection = $this->addHeidelpayNotificationRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerEco\Yves\Heidelpay\Controller\HeidelpayController::paymentAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addHeidelpayPaymentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::ROUTE_NAME_HEIDELPAY_PAYMENT,
            $this->buildRoute('/heidelpay/payment', static::HEIDELPAY_BUNDLE_NAME, 'Heidelpay', 'payment'),
        );

        return $routeCollection;
    }

    /**
     * @uses \SprykerEco\Yves\Heidelpay\Controller\HeidelpayController::paymentFailedAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addHeidelpayPaymentFailedRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::ROUTE_NAME_HEIDELPAY_PAYMENT_FAILED,
            $this->buildRoute('/heidelpay/payment-failed', static::HEIDELPAY_BUNDLE_NAME, 'Heidelpay', 'paymentFailed'),
        );

        return $routeCollection;
    }

    /**
     * @uses \SprykerEco\Yves\Heidelpay\Controller\EasyCreditController::easyCreditPaymentAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addHeidelpayEasyCreditPaymentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::ROUTE_NAME_HEIDELPAY_EASY_CREDIT_PAYMENT,
            $this->buildRoute('/heidelpay/easyCreditPayment', static::HEIDELPAY_BUNDLE_NAME, 'EasyCredit', 'easyCreditPayment'),
        );

        return $routeCollection;
    }

    /**
     * @uses \SprykerEco\Yves\Heidelpay\Controller\EasyCreditController::easyCreditInitializePaymentAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addHeidelpayEasyCreditInitializePaymentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::ROUTE_NAME_HEIDELPAY_EASY_CREDIT_INITIALIZE_PAYMENT,
            $this->buildRoute('/heidelpay/easyCreditInitializePayment', static::HEIDELPAY_BUNDLE_NAME, 'EasyCredit', 'easyCreditInitializePayment'),
        );

        return $routeCollection;
    }

    /**
     * @uses \SprykerEco\Yves\Heidelpay\Controller\IdealController::authorizeAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addHeidelpayIdealAuthorizeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::ROUTE_NAME_HEIDELPAY_IDEAL_AUTHORIZE,
            $this->buildRoute('/heidelpay/ideal-authorize', static::HEIDELPAY_BUNDLE_NAME, 'Ideal', 'authorize'),
        );

        return $routeCollection;
    }

    /**
     * @uses \SprykerEco\Yves\Heidelpay\Controller\CreditCardController::registrationRequestAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addHeidelpayCreditCardRegisterRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::ROUTE_NAME_HEIDELPAY_CREDIT_CARD_REGISTER,
            $this->buildRoute('/heidelpay/cc-register-response', static::HEIDELPAY_BUNDLE_NAME, 'CreditCard', 'registrationRequest'),
        );

        return $routeCollection;
    }

    /**
     * @uses \SprykerEco\Yves\Heidelpay\Controller\CreditCardController::registrationSuccessAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addHeidelpayCreditCardRegisterSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::ROUTE_NAME_HEIDELPAY_CREDIT_CARD_REGISTER_SUCCESS,
            $this->buildRoute('/heidelpay/cc-register-success', static::HEIDELPAY_BUNDLE_NAME, 'CreditCard', 'registrationSuccess'),
        );

        return $routeCollection;
    }

    /**
     * @uses \SprykerEco\Yves\Heidelpay\Controller\DirectDebitController::registrationRequestAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addHeidelpayDirectDebitRegisterRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::ROUTE_NAME_HEIDELPAY_DIRECT_DEBIT_REGISTER,
            $this->buildPostRoute('/heidelpay/dd-register-response', static::HEIDELPAY_BUNDLE_NAME, 'DirectDebit', 'registrationRequest'),
        );

        return $routeCollection;
    }

    /**
     * @uses \SprykerEco\Yves\Heidelpay\Controller\DirectDebitController::registrationSuccessAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addHeidelpayDirectDebitRegisterSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::ROUTE_NAME_HEIDELPAY_DIRECT_DEBIT_REGISTER_SUCCESS,
            $this->buildRoute('/heidelpay/dd-register-success', static::HEIDELPAY_BUNDLE_NAME, 'DirectDebit', 'registrationSuccess'),
        );

        return $routeCollection;
    }

    /**
     * @uses \SprykerEco\Yves\Heidelpay\Controller\NotificationController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addHeidelpayNotificationRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::ROUTE_NAME_HEIDELPAY_NOTIFICATION,
            $this->buildPostRoute('/heidelpay/notification', static::HEIDELPAY_BUNDLE_NAME, 'Notification', 'index'),
        );

        return $routeCollection;
    }
}
