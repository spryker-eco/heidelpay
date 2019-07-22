<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Expander;

use Generated\Shared\Transfer\HeidelpayNotificationTransfer;
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

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Converter\NotificationXmlConverterInterface
     */
    protected $xmlConverter;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Processor\Notification\Converter\NotificationXmlConverterInterface $xmlConverter
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Service\HeidelpayToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        NotificationXmlConverterInterface $xmlConverter,
        HeidelpayToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->xmlConverter = $xmlConverter;
        $this->utilEncodingService = $utilEncodingService;
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
        $notificationTransfer->setTransactionSource($transactionData['source']);
        $notificationTransfer->setTransactionChannel($transactionData['channel']);
        $notificationTransfer->setTransactionResponseType($transactionData['response']);
        $notificationTransfer->setTransactionMode($transactionData['mode']);

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
        $notificationTransfer->setTransactionId($identificationData['TransactionID']);
        $notificationTransfer->setUniqueId($identificationData['UniqueID']);
        $notificationTransfer->setShortId($identificationData['ShortID']);
        $notificationTransfer->setIdentificationSource($identificationData['Source']);

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
        $notificationTransfer->setResultCode($processingData[static::KEY_ATTRIBUTES]['code']);
        $notificationTransfer->setResultTimestamp($processingData['Timestamp']);
        $notificationTransfer->setResult($processingData['Result']);
        $notificationTransfer->setResultStatus($processingData['Status']);
        $notificationTransfer->setResultReason($processingData['Reason']);
        $notificationTransfer->setResultReturn($processingData['Return']);

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
        $notificationTransfer->setPaymentCode($paymentData[static::KEY_ATTRIBUTES]['code']);
        $notificationTransfer->setAmount($paymentData[static::KEY_CLEARING]['Amount']);
        $notificationTransfer->setCurrency($paymentData[static::KEY_CLEARING]['Currency']);
        $notificationTransfer->setPaymentDescriptor($paymentData[static::KEY_CLEARING]['Descriptor']);

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
}
