<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Request;

use Generated\Shared\Transfer\HeidelpayRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Heidelpay\Business\Mapper\OrderToHeidelpayRequestInterface;
use SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayConfig;

class AdapterRequestFromOrderBuilder extends BaseAdapterRequestBuilder implements AdapterRequestFromOrderBuilderInterface
{

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Mapper\OrderToHeidelpayRequestInterface
     */
    protected $orderToHeidelpayMapper;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Mapper\OrderToHeidelpayRequestInterface $orderToHeidelpayMapper
     * @param \SprykerEco\Zed\Heidelpay\Dependency\Facade\HeidelpayToCurrencyInterface $currencyFacade
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfig $config
     */
    public function __construct(
        OrderToHeidelpayRequestInterface $orderToHeidelpayMapper,
        HeidelpayToCurrencyInterface $currencyFacade,
        HeidelpayConfig $config
    ) {
        parent::__construct($currencyFacade, $config);
        $this->orderToHeidelpayMapper = $orderToHeidelpayMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function buildAuthorizeRequestFromOrder(OrderTransfer $orderTransfer)
    {
        return $this->buildBaseOrderHeidelpayRequest($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function buildDebitRequestFromOrder(OrderTransfer $orderTransfer)
    {
        return $this->buildBaseOrderHeidelpayRequest($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    public function buildCaptureRequestFromOrder(OrderTransfer $orderTransfer)
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
    protected function buildBaseOrderHeidelpayRequest(OrderTransfer $orderTransfer)
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
    protected function hydrateOrder(HeidelpayRequestTransfer $heidelpayRequestTransfer, OrderTransfer $orderTransfer)
    {
        $this->orderToHeidelpayMapper->map($orderTransfer, $heidelpayRequestTransfer);

        return $heidelpayRequestTransfer;
    }

}
