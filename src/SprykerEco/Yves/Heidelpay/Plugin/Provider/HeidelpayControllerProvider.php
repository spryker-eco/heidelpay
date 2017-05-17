<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Plugin\Provider;

use Silex\Application;
use Spryker\Yves\Application\Plugin\Provider\YvesControllerProvider;

class HeidelpayControllerProvider extends YvesControllerProvider
{

    const HEIDELPAY_PAYMENT = 'heidelpay-payment';
    const HEIDELPAY_PAYMENT_FAILED = 'heidelpay-payment-failed';
    const HEIDELPAY_IDEAL_AUTHORIZE = 'heidelpay-ideal-authorize';
    const HEIDELPAY_CREDIT_CARD_REGISTER = 'heidelpay-cc-register';
    const HEIDELPAY_CREDIT_CARD_REGISTER_SUCCESS = 'heidelpay-cc-register-success';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
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
