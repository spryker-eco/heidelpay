<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\HeidelpayBankCountryTransfer;
use Generated\Shared\Transfer\HeidelpayBankTransfer;
use Generated\Shared\Transfer\HeidelpayResponseConfigTransfer;
use Generated\Shared\Transfer\HeidelpayResponseErrorTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Heidelpay\PhpPaymentApi\Exceptions\PaymentFormUrlException;
use Heidelpay\PhpPaymentApi\ParameterGroups\ConfigParameterGroup;
use Heidelpay\PhpPaymentApi\Response;
use SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface;

class ResponseFromHeidelpay implements ResponseFromHeidelpayInterface
{
    /**
     * @var string
     */
    public const RESPONSE_PARAMETER_GROUP_PROCESSING = 'processing';

    /**
     * @var string
     */
    public const RESPONSE_PARAMETER_GROUP_ACCOUNT = 'account';

    /**
     * @var string
     */
    public const RESPONSE_PARAMETER_GROUP_ADDRESS = 'address';

    /**
     * @var string
     */
    public const RESPONSE_PARAMETER_GROUP_CONFIG = 'config';

    /**
     * @var string
     */
    public const RESPONSE_PARAMETER_GROUP_CONTACT = 'contact';

    /**
     * @var string
     */
    public const RESPONSE_PARAMETER_GROUP_CRITERION = 'criterion';

    /**
     * @var string
     */
    public const RESPONSE_PARAMETER_GROUP_FRONTEND = 'frontend';

    /**
     * @var string
     */
    public const RESPONSE_PARAMETER_GROUP_IDENTIFICATION = 'identification';

    /**
     * @var string
     */
    public const RESPONSE_PARAMETER_GROUP_NAME = 'name';

    /**
     * @var string
     */
    public const RESPONSE_PARAMETER_GROUP_PAYMENT = 'payment';

    /**
     * @var string
     */
    public const RESPONSE_PARAMETER_GROUP_PRESENTATION = 'presentation';

    /**
     * @var string
     */
    public const RESPONSE_PARAMETER_GROUP_REQUEST = 'request';

    /**
     * @var string
     */
    public const RESPONSE_PARAMETER_GROUP_SECURITY = 'security';

    /**
     * @var string
     */
    public const RESPONSE_PARAMETER_GROUP_TRANSACTION = 'transaction';

    /**
     * @var string
     */
    public const RESPONSE_PARAMETER_GROUP_USER = 'user';

    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface
     */
    protected $utilEncoding;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface $utilEncoding
     */
    public function __construct(HeidelpayToUtilEncodingServiceInterface $utilEncoding)
    {
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function map(Response $apiResponse, HeidelpayResponseTransfer $responseTransfer): void
    {
        $responseTransfer
            ->setIsPending($apiResponse->isPending())
            ->setIsSuccess($apiResponse->isSuccess())
            ->setIdSalesOrder((int)$apiResponse->getIdentification()->getTransactionId())
            ->setIsError($apiResponse->isError())
            ->setProcessingCode($apiResponse->getProcessing()->code)
            ->setIdTransactionUnique($apiResponse->getIdentification()->getUniqueId())
            ->setResultCode($apiResponse->getProcessing()->getResult())
            ->setLegalText($apiResponse->getConfig()->getOptinText())
            ->setConnectorInvoiceAccountInfo($this->getConnectorInfo($apiResponse));

        $this->mapPaymentFormUrl($apiResponse, $responseTransfer);
        $this->mapError($apiResponse, $responseTransfer);
        $this->mapConfig($apiResponse, $responseTransfer);

        $this->addResponseFullPayload($apiResponse, $responseTransfer);
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function mapError(Response $apiResponse, HeidelpayResponseTransfer $responseTransfer): void
    {
        if (!$apiResponse->isError()) {
            return;
        }

        $errorResponse = $apiResponse->getError();
        $errorTransfer = (new HeidelpayResponseErrorTransfer())
            ->setCode($errorResponse['code'])
            ->setInternalMessage($errorResponse['message']);

        $responseTransfer
            ->setIsError(true)
            ->setError($errorTransfer);
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function mapConfig(Response $apiResponse, HeidelpayResponseTransfer $responseTransfer): void
    {
        $responseConfig = $apiResponse->getConfig();
        $configTransfer = new HeidelpayResponseConfigTransfer();

        $this->mapBanks($responseConfig, $configTransfer);
        $this->mapBankCountries($responseConfig, $configTransfer);

        $responseTransfer->setConfig($configTransfer);
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\ParameterGroups\ConfigParameterGroup $config
     * @param \Generated\Shared\Transfer\HeidelpayResponseConfigTransfer $configTransfer
     *
     * @return void
     */
    protected function mapBanks(ConfigParameterGroup $config, HeidelpayResponseConfigTransfer $configTransfer): void
    {
        /** @var array<string> $banks */
        $banks = $config->getBrands();

        if (empty($banks)) {
            return;
        }

        $bankTransfersList = [];
        foreach ($banks as $code => $name) {
            $bankTransfersList[] = (new HeidelpayBankTransfer())
                ->setCode($code)
                ->setName($name);
        }

        $configTransfer->setBankBrands(new ArrayObject($bankTransfersList));
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\ParameterGroups\ConfigParameterGroup $config
     * @param \Generated\Shared\Transfer\HeidelpayResponseConfigTransfer $configTransfer
     *
     * @return void
     */
    protected function mapBankCountries(ConfigParameterGroup $config, HeidelpayResponseConfigTransfer $configTransfer): void
    {
        /** @var array<string> $bankCountries */
        $bankCountries = $config->getBankCountry();

        if (empty($bankCountries)) {
            return;
        }

        $countryTransfersList = [];
        foreach ($bankCountries as $code => $name) {
            $countryTransfersList[] = (new HeidelpayBankCountryTransfer())
                ->setCode($code)
                ->setName($name);
        }

        $configTransfer->setBankCountries(new ArrayObject($countryTransfersList));
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function addResponseFullPayload(Response $apiResponse, HeidelpayResponseTransfer $responseTransfer): void
    {
        $responseTransfer->setPayload(
            $this->getJsonEncodedPayloadFromApiResponse($apiResponse),
        );
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponse
     *
     * @return string|null
     */
    protected function getJsonEncodedPayloadFromApiResponse(Response $apiResponse): ?string
    {
        $payload = [
            static::RESPONSE_PARAMETER_GROUP_PROCESSING => get_object_vars($apiResponse->getProcessing()),
            static::RESPONSE_PARAMETER_GROUP_ACCOUNT => get_object_vars($apiResponse->getAccount()),
            static::RESPONSE_PARAMETER_GROUP_ADDRESS => get_object_vars($apiResponse->getAddress()),
            static::RESPONSE_PARAMETER_GROUP_CONFIG => get_object_vars($apiResponse->getConfig()),
            static::RESPONSE_PARAMETER_GROUP_CONTACT => get_object_vars($apiResponse->getContact()),
            static::RESPONSE_PARAMETER_GROUP_CRITERION => get_object_vars($apiResponse->getCriterion()),
            static::RESPONSE_PARAMETER_GROUP_FRONTEND => get_object_vars($apiResponse->getFrontend()),
            static::RESPONSE_PARAMETER_GROUP_IDENTIFICATION => get_object_vars($apiResponse->getIdentification()),
            static::RESPONSE_PARAMETER_GROUP_NAME => get_object_vars($apiResponse->getName()),
            static::RESPONSE_PARAMETER_GROUP_PAYMENT => get_object_vars($apiResponse->getPayment()),
            static::RESPONSE_PARAMETER_GROUP_PRESENTATION => get_object_vars($apiResponse->getPresentation()),
            static::RESPONSE_PARAMETER_GROUP_REQUEST => get_object_vars($apiResponse->getRequest()),
            static::RESPONSE_PARAMETER_GROUP_SECURITY => get_object_vars($apiResponse->getSecurity()),
            static::RESPONSE_PARAMETER_GROUP_TRANSACTION => get_object_vars($apiResponse->getTransaction()),
            static::RESPONSE_PARAMETER_GROUP_USER => get_object_vars($apiResponse->getUser()),
        ];

        return $this->utilEncoding->encodeJson($payload);
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponse
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function mapPaymentFormUrl(
        Response $apiResponse,
        HeidelpayResponseTransfer $responseTransfer
    ): void {
        try {
            /** @var string $paymentFormUrl */
            $paymentFormUrl = $apiResponse->getPaymentFormUrl();
            $responseTransfer->setPaymentFormUrl($paymentFormUrl);
        } catch (PaymentFormUrlException $exception) {
            $responseTransfer->setPaymentFormUrl(null);
        }
    }

    /**
     * @param \Heidelpay\PhpPaymentApi\Response $apiResponse
     *
     * @return string|null
     */
    protected function getConnectorInfo(Response $apiResponse): ?string
    {
        if (empty($apiResponse->getConnector()->getAccountIBan())) {
            return null;
        }

        return $apiResponse->getConnector()->toJson();
    }
}
