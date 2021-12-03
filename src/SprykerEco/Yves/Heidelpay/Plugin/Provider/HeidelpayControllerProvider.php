<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use {@link \SprykerEco\Yves\Heidelpay\Plugin\Router\HeidelpayRouteProviderPlugin} instead.
 */
class HeidelpayControllerProvider extends AbstractYvesControllerProvider
{
    /**
     * @var string
     */
    public const HEIDELPAY_PAYMENT = 'heidelpay-payment';
    /**
     * @var string
     */
    public const HEIDELPAY_EASY_CREDIT_PAYMENT = 'heidelpay-easy-credit-payment';
    /**
     * @var string
     */
    public const HEIDELPAY_EASY_CREDIT_INITIALIZE_PAYMENT = 'heidelpay-easy-credit-initialize-payment';
    /**
     * @var string
     */
    public const HEIDELPAY_PAYMENT_FAILED = 'heidelpay-payment-failed';
    /**
     * @var string
     */
    public const HEIDELPAY_IDEAL_AUTHORIZE = 'heidelpay-ideal-authorize';
    /**
     * @var string
     */
    public const HEIDELPAY_CREDIT_CARD_REGISTER = 'heidelpay-cc-register';
    /**
     * @var string
     */
    public const HEIDELPAY_CREDIT_CARD_REGISTER_SUCCESS = 'heidelpay-cc-register-success';
    /**
     * @var string
     */
    public const HEIDELPAY_NOTIFICATION = 'heidelpay-notification';
    /**
     * @var string
     */
    public const HEIDELPAY_DIRECT_DEBIT_REGISTER = 'heidelpay-dd-register';
    /**
     * @var string
     */
    public const HEIDELPAY_DIRECT_DEBIT_REGISTER_SUCCESS = 'heidelpay-dd-register-success';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->createController(
            '/heidelpay/payment-failed',
            static::HEIDELPAY_PAYMENT_FAILED,
            'Heidelpay',
            'Heidelpay',
            'paymentFailed'
        );

        $this->createController(
            '/heidelpay/payment',
            static::HEIDELPAY_PAYMENT,
            'Heidelpay',
            'Heidelpay',
            'payment'
        );

        $this->createController(
            '/heidelpay/easyCreditPayment',
            static::HEIDELPAY_EASY_CREDIT_PAYMENT,
            'Heidelpay',
            'EasyCredit',
            'easyCreditPayment'
        );

        $this->createController(
            '/heidelpay/easyCreditInitializePayment',
            static::HEIDELPAY_EASY_CREDIT_INITIALIZE_PAYMENT,
            'Heidelpay',
            'EasyCredit',
            'easyCreditInitializePayment'
        );

        $this->createController(
            '/heidelpay/ideal-authorize',
            static::HEIDELPAY_IDEAL_AUTHORIZE,
            'Heidelpay',
            'Ideal',
            'authorize'
        );

        $this->createController(
            '/heidelpay/cc-register-response',
            static::HEIDELPAY_CREDIT_CARD_REGISTER,
            'Heidelpay',
            'CreditCard',
            'registrationRequest'
        );

        $this->createController(
            '/heidelpay/cc-register-success',
            static::HEIDELPAY_CREDIT_CARD_REGISTER_SUCCESS,
            'Heidelpay',
            'CreditCard',
            'registrationSuccess'
        );

        $this->createPostController(
            '/heidelpay/notification',
            static::HEIDELPAY_NOTIFICATION,
            'Heidelpay',
            'Notification',
            'index'
        );

        $this->createPostController(
            '/heidelpay/dd-register-response',
            static::HEIDELPAY_DIRECT_DEBIT_REGISTER,
            'Heidelpay',
            'DirectDebit',
            'registrationRequest'
        );

        $this->createController(
            '/heidelpay/dd-register-success',
            static::HEIDELPAY_DIRECT_DEBIT_REGISTER_SUCCESS,
            'Heidelpay',
            'DirectDebit',
            'registrationSuccess'
        );
    }
}
