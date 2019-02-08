<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponse;

use SprykerEcoTest\Shared\Heidelpay\HeidelpayTestConstants;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\ExternalResponseBuilder;
use SprykerEcoTest\Zed\Heidelpay\Business\DataProviders\Response\HeidelpayEasyCreditResponseTrait;

class UnsuccessEasyCreditPaymentExternalResponseBuilder extends ExternalResponseBuilder
{
    use HeidelpayEasyCreditResponseTrait;

    public function createHeidelpayResponse(string $paymentMethod): array
    {
        $customerJohnDoe = $this->createOrGetCustomerJohnDoe();
        $billingAddressJohnDoe = $shippingAddressJohnDoe = $this->createOrderAddressJohnDoe();

        $orderEntity = $this->createOrderEntityWithItems(
            $customerJohnDoe,
            $billingAddressJohnDoe,
            $shippingAddressJohnDoe
        );

        $this->createHeidelpayPaymentEntity(
            $orderEntity,
            '',
            $paymentMethod
        );

        $config = $this->factory->getConfig();
        $responseParam[static::EMAIL] = $customerJohnDoe->getEmail();
        $responseParam[static::RESPONSE_URL] = $config->getZedResponseUrl();
        $responseParam[static::CUSTOMER_NAME] = $customerJohnDoe->getFirstName();
        $responseParam[static::CUSTOMER_FULL_NAME] = $customerJohnDoe->getFirstName();

        $responseParam[static::AMOUNT] = $orderEntity->getLastOrderTotals()->getGrandTotal();

        $responseParam[static::TRANSACRTION_ID] = $this->getTransationId($orderEntity);
        $responseParam[static::IDENTIFICATION_UNIQUEID] = $this->getIdentificationUniqueId($orderEntity);

        $responseParam[static::TRANSACRTION_CHANNEL] = $config->getMerchantTransactionChannelByPaymentType($paymentMethod);
        $responseParam[static::SECURITY_SENDER] = $config->getMerchantSecuritySender();
        $responseParam[static::USER_LOGIN] = $config->getMerchantUserLogin();
        $responseParam[static::USER_PWD] = $config->getMerchantUserPassword();

        $responseParam[static::CRITERION_SDK_NAME] = static::CRITERION_SDK_VALUE;

        $responseParam[static::CRITERION_SECRET] = $this->getCriterionSecret($this->getTransationId($orderEntity), $config->getApplicationSecret());

        $responseParam[static::PAYMENT_BRAND] = $this->getPaymentBrand($paymentMethod);
        $responseParam[static::PAYMENT_METHOD] = $this->getClassName($paymentMethod);
        $responseParam[static::PROCESSING_RETURN] = $this->getProcessingReturn();

        $responseParam[static::PROCESSING_RESULT] = HeidelpayTestConstants::HEIDELPAY_UNSUCCESS_RESPONSE;
        $responseParam[static::PAYMENT_CODE] = self::PAYMENT_CODE_HP_INI;
        $responseParam[static::PROCESSING_STATUS_CODE] = '90';
        $responseParam[static::PROCESSING_REASON_CODE] = '00';

        $response = $this->getHeidelpayResponseTemplate($responseParam);

        return $response;
    }

    /**
     * @return string|null
     */
    protected function getProcessingReturn(): string
    {
        return 'The response object seems to be empty or it is not a valid heidelpay response!';
    }

}
