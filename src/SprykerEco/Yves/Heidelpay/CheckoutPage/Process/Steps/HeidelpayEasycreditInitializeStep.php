<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\HeidelpayPaymentTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Client\Heidelpay\HeidelpayClientInterface;
use SprykerEco\Shared\Heidelpay\HeidelpayConfig;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AbstractBaseStep;
use Symfony\Component\HttpFoundation\Request;

class HeidelpayEasycreditInitializeStep extends AbstractBaseStep
{
    /**
     * @var \SprykerEco\Client\Heidelpay\HeidelpayClientInterface
     */
    protected $heidelpayClient;

    /**
     * @var string
     */
    protected $externalRedirectUrl;

    /**
     * @param string $stepRoute
     * @param string $escapeRoute
     * @param \SprykerEco\Client\Heidelpay\HeidelpayClientInterface $heidelpayClient
     */
    public function __construct(
        string $stepRoute,
        string $escapeRoute,
        HeidelpayClientInterface $heidelpayClient
    ) {
        parent::__construct($stepRoute, $escapeRoute);

        $this->heidelpayClient = $heidelpayClient;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        return false;
    }

    /**
     * Empty quote transfer and mark logged in customer as "dirty" to force update it in the next request.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        $easyCreditInitializeResponse = $this->getEasyCreditInitializeResponse($quoteTransfer);

        if ($easyCreditInitializeResponse->getIsSuccess() === true) {
            if (!$quoteTransfer->getHeidelpayPayment()) {
                $quoteTransfer->setHeidelpayPayment(new HeidelpayPaymentTransfer());
            }
            $quoteTransfer->getHeidelpayPayment()->setLegalText($easyCreditInitializeResponse->getLegalText());
            $quoteTransfer->getHeidelpayPayment()->setExternalRedirectUrl($easyCreditInitializeResponse->getPaymentFormUrl());
        }

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    protected function getEasyCreditInitializeResponse(QuoteTransfer $quoteTransfer): HeidelpayResponseTransfer
    {
        $quoteTransferMock = clone($quoteTransfer);
        $quoteTransferMock->setPayment(
            (new PaymentTransfer())
                ->setPaymentMethod(HeidelpayConfig::PAYMENT_METHOD_EASY_CREDIT)
        );

        return $this->heidelpayClient->sendHeidelpayEasycreditInitializeRequest($quoteTransferMock);
    }
}
