<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Request;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Heidelpay\Business\Mapper\OrderToHeidelpayRequestInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReaderInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyFacadeInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayConfig;

class AdapterRequestFromOrderBuilder extends BaseAdapterRequestBuilder implements AdapterRequestFromOrderBuilderInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Mapper\OrderToHeidelpayRequestInterface
     */
    protected $orderToHeidelpayMapper;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReaderInterface
     */
    protected $paymentReader;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Mapper\OrderToHeidelpayRequestInterface $orderToHeidelpayMapper
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyFacadeInterface $currencyFacade
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfig $config
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\PaymentReaderInterface $paymentReader
     */
    public function __construct(
        OrderToHeidelpayRequestInterface $orderToHeidelpayMapper,
        HeidelpayToCurrencyFacadeInterface $currencyFacade,
        HeidelpayConfig $config,
        PaymentReaderInterface $paymentReader
    ) {
        parent::__construct($currencyFacade, $config);
        $this->orderToHeidelpayMapper = $orderToHeidelpayMapper;
        $this->paymentReader = $paymentReader;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function buildAuthorizeRequestFromOrder(OrderTransfer $orderTransfer): HeidelpayRequestTransfer
    {
        return $this->buildBaseOrderHeidelpayRequest($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function buildDebitRequestFromOrder(OrderTransfer $orderTransfer): HeidelpayRequestTransfer
    {
        return $this->buildBaseOrderHeidelpayRequest($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function buildReservationRequestFromOrder(OrderTransfer $orderTransfer): HeidelpayRequestTransfer
    {
        return $this->buildBaseOrderHeidelpayRequest($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function buildAuthorizeOnRegistrationRequestFromOrder(OrderTransfer $orderTransfer): HeidelpayRequestTransfer
    {
        $requestTransfer = $this->buildBaseOrderHeidelpayRequest($orderTransfer);
        $requestTransfer->setIdPaymentReference(
            $orderTransfer->getHeidelpayPayment()->getIdPaymentReference()
        );

        return $requestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function buildFinalizeRequestFromOrder(OrderTransfer $orderTransfer): HeidelpayRequestTransfer
    {
        $requestTransfer = $this->buildBaseOrderHeidelpayRequest($orderTransfer);
        $requestTransfer->setIdPaymentReference(
            $orderTransfer->getHeidelpayPayment()->getIdPaymentReference()
        );

        return $requestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function buildCaptureRequestFromOrder(OrderTransfer $orderTransfer): HeidelpayRequestTransfer
    {
        $requestTransfer = $this->buildBaseOrderHeidelpayRequest($orderTransfer);
        $requestTransfer->setIdPaymentReference($orderTransfer->getHeidelpayPayment()->getIdPaymentReference());

        return $requestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function buildBaseOrderHeidelpayRequest(OrderTransfer $orderTransfer): HeidelpayRequestTransfer
    {
        $requestTransfer = new HeidelpayRequestTransfer();

        $requestTransfer = $this->hydrateOrder($requestTransfer, $orderTransfer);
        $requestTransfer = $this->hydrateRequestData($requestTransfer);

        $paymentMethod = $orderTransfer->getHeidelpayPayment()->getPaymentMethod();

        $requestTransfer = $this->hydrateTransactionChannel($requestTransfer, $paymentMethod);

        return $requestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayRequestTransfer $heidelpayRequestTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function hydrateOrder(
        HeidelpayRequestTransfer $heidelpayRequestTransfer,
        OrderTransfer $orderTransfer
    ): HeidelpayRequestTransfer {
        return $this->orderToHeidelpayMapper
            ->map($orderTransfer, $heidelpayRequestTransfer);
    }
}
