<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class HeidelpayControllerProvider extends AbstractYvesControllerProvider
{
    public const HEIDELPAY_PAYMENT = 'heidelpay-payment';
    public const HEIDELPAY_EASY_CREDIT_PAYMENT = 'heidelpay-easy-credit-payment';
    public const HEIDELPAY_PAYMENT_FAILED = 'heidelpay-payment-failed';
    public const HEIDELPAY_IDEAL_AUTHORIZE = 'heidelpay-ideal-authorize';
    public const HEIDELPAY_CREDIT_CARD_REGISTER = 'heidelpay-cc-register';
    public const HEIDELPAY_CREDIT_CARD_REGISTER_SUCCESS = 'heidelpay-cc-register-success';

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
    }
}
