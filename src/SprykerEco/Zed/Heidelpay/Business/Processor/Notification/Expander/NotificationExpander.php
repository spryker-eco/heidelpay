<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Expander;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Converter\NotificationXmlConverterInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface;

class NotificationExpander implements NotificationExpanderInterface
{
    protected const KEY_ATTRIBUTES = '@attributes';
    protected const KEY_IDENTIFICATION = 'Identification';
    protected const KEY_PROCESSING = 'Processing';
    protected const KEY_PAYMENT = 'Payment';
    protected const KEY_CLEARING = 'Clearing';
    protected const KEY_ACCOUNT = 'Account';
    protected const KEY_CUSTOMER = 'Customer';
    protected const KEY_TRANSACTION_SOURCE = 'source';
    protected const KEY_CHANNEL = 'channel';
    protected const KEY_RESPONSE = 'response';
    protected const KEY_MODE = 'mode';
    protected const KEY_TRANSACTION_ID = 'TransactionID';
    protected const KEY_UNIQUE_ID = 'UniqueID';
    protected const KEY_SHORT_ID = 'ShortID';
    protected const KEY_IDENTIFICATION_SOURCE = 'Source';
    protected const KEY_CODE = 'code';
    protected const KEY_TIMESTAMP = 'Timestamp';
    protected const KEY_RESULT = 'Result';
    protected const KEY_STATUS = 'Status';
    protected const KEY_REASON = 'Reason';
    protected const KEY_RETURN = 'Return';
    protected const KEY_AMOUNT = 'Amount';
    protected const KEY_CURRENCY = 'Currency';
    protected const KEY_DESCRIPTOR = 'Descriptor';

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Converter\NotificationXmlConverterInterface
     */
    protected $xmlConverter;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Plugin\HeidelpayNotificationExpanderPluginInterface[]
     */
    protected $notificationExpanderPlugins;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Converter\NotificationXmlConverterInterface $xmlConverter
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Plugin\HeidelpayNotificationExpanderPluginInterface[] $notificationExpanderPlugins
     */
    public function __construct(
        NotificationXmlConverterInterface $xmlConverter,
        HeidelpayToUtilEncodingServiceInterface $utilEncodingService,
        MoneyPluginInterface $moneyPlugin,
        array $notificationExpanderPlugins = []
    ) {
        $this->xmlConverter = $xmlConverter;
        $this->utilEncodingService = $utilEncodingService;
        $this->moneyPlugin = $moneyPlugin;
        $this->notificationExpanderPlugins = $notificationExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    public function expandWithNotificationData(HeidelpayNotificationTransfer $notificationTransfer): HeidelpayNotificationTransfer
    {
        $notificationData = $this->xmlConverter->convert($notificationTransfer->getNotificationBody());

        $notificationTransfer = $this->addTransactionData($notificationTransfer, $notificationData[static::KEY_ATTRIBUTES]);
        $notificationTransfer = $this->addIdentificationData($notificationTransfer, $notificationData[static::KEY_IDENTIFICATION]);
        $notificationTransfer = $this->addProcessingData($notificationTransfer, $notificationData[static::KEY_PROCESSING]);
        $notificationTransfer = $this->addPaymentData($notificationTransfer, $notificationData[static::KEY_PAYMENT]);
        $notificationTransfer = $this->addAccountData($notificationTransfer, $notificationData[static::KEY_ACCOUNT]);
        $notificationTransfer = $this->addCustomerData($notificationTransfer, $notificationData[static::KEY_CUSTOMER]);

        $notificationTransfer = $this->executeExpanderPlugins($notificationTransfer, $notificationData);

        return $notificationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     * @param array $transactionData
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    protected function addTransactionData(
        HeidelpayNotificationTransfer $notificationTransfer,
        array $transactionData
    ): HeidelpayNotificationTransfer {
        $notificationTransfer
            ->setTransactionSource($transactionData[static::KEY_TRANSACTION_SOURCE])
            ->setTransactionChannel($transactionData[static::KEY_CHANNEL])
            ->setTransactionResponseType($transactionData[static::KEY_RESPONSE])
            ->setTransactionMode($transactionData[static::KEY_MODE]);

        return $notificationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     * @param array $identificationData
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    protected function addIdentificationData(
        HeidelpayNotificationTransfer $notificationTransfer,
        array $identificationData
    ): HeidelpayNotificationTransfer {
        $notificationTransfer
            ->setTransactionId($identificationData[static::KEY_TRANSACTION_ID])
            ->setUniqueId($identificationData[static::KEY_UNIQUE_ID])
            ->setShortId($identificationData[static::KEY_SHORT_ID])
            ->setIdentificationSource($identificationData[static::KEY_IDENTIFICATION_SOURCE]);

        return $notificationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     * @param array $processingData
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    protected function addProcessingData(
        HeidelpayNotificationTransfer $notificationTransfer,
        array $processingData
    ): HeidelpayNotificationTransfer {
        $notificationTransfer
            ->setResultCode($processingData[static::KEY_ATTRIBUTES][static::KEY_CODE])
            ->setResultTimestamp($processingData[static::KEY_TIMESTAMP])
            ->setResult($processingData[static::KEY_RESULT])
            ->setResultStatus($processingData[static::KEY_STATUS])
            ->setResultReason($processingData[static::KEY_REASON])
            ->setResultReturn($processingData[static::KEY_RETURN]);

        return $notificationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     * @param array $paymentData
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    protected function addPaymentData(
        HeidelpayNotificationTransfer $notificationTransfer,
        array $paymentData
    ): HeidelpayNotificationTransfer {
        $notificationTransfer
            ->setPaymentCode($paymentData[static::KEY_ATTRIBUTES][static::KEY_CODE])
            ->setCurrency($paymentData[static::KEY_CLEARING][static::KEY_CURRENCY])
            ->setPaymentDescriptor($paymentData[static::KEY_CLEARING][static::KEY_DESCRIPTOR])
            ->setAmount(
                $this->convertAmountToInt($paymentData[static::KEY_CLEARING][static::KEY_AMOUNT])
            );

        return $notificationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     * @param array $accountData
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    protected function addAccountData(
        HeidelpayNotificationTransfer $notificationTransfer,
        array $accountData
    ): HeidelpayNotificationTransfer {
        $notificationTransfer->setAccount(
            $this->utilEncodingService->encodeJson($accountData)
        );

        return $notificationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     * @param array $customerData
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    protected function addCustomerData(
        HeidelpayNotificationTransfer $notificationTransfer,
        array $customerData
    ): HeidelpayNotificationTransfer {
        $notificationTransfer->setCustomer(
            $this->utilEncodingService->encodeJson($customerData)
        );

        return $notificationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayNotificationTransfer $notificationTransfer
     * @param string[][] $notificationData
     *
     * @return \Generated\Shared\Transfer\HeidelpayNotificationTransfer
     */
    protected function executeExpanderPlugins(
        HeidelpayNotificationTransfer $notificationTransfer,
        array $notificationData
    ): HeidelpayNotificationTransfer {
        foreach ($this->notificationExpanderPlugins as $notificationExpanderPlugin) {
            $notificationTransfer = $notificationExpanderPlugin->expand($notificationTransfer, $notificationData);
        }

        return $notificationTransfer;
    }

    /**
     * @param string $amount
     *
     * @return int
     */
    protected function convertAmountToInt(string $amount): int
    {
        return $this->moneyPlugin->convertDecimalToInteger((float)$amount);
    }
}
