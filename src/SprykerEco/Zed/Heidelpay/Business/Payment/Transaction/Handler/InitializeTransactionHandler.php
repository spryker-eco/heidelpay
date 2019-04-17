<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\HeidelpayResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Heidelpay\Business\Basket\BasketCreatorInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderInterface;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\InitializeNotSupportedException;
use SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\InitializeTransactionInterface;

class InitializeTransactionHandler implements InitializeTransactionHandlerInterface
{
    public const ERROR_MESSAGE_INITIALIZE_TRANSACTION_NOT_SUPPORTED =
        'Attempt to call initialize transaction on payment method \'%s\' ' .
        'that does not support it';

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\InitializeTransactionInterface
     */
    protected $transaction;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithInitializeInterface[]
     */
    protected $paymentMethodAdapterCollection;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderInterface
     */
    protected $heidelpayRequestBuilder;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Basket\BasketCreatorInterface
     */
    protected $basketCreator;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\InitializeTransactionInterface $transaction
     * @param array $paymentMethodAdapterCollection
     * @param \SprykerEco\Zed\Heidelpay\Business\Payment\Request\AdapterRequestFromQuoteBuilderInterface $heidelpayRequestBuilder
     * @param \SprykerEco\Zed\Heidelpay\Business\Basket\BasketCreatorInterface $basketCreator
     */
    public function __construct(
        InitializeTransactionInterface $transaction,
        array $paymentMethodAdapterCollection,
        AdapterRequestFromQuoteBuilderInterface $heidelpayRequestBuilder,
        BasketCreatorInterface $basketCreator
    ) {
        $this->transaction = $transaction;
        $this->paymentMethodAdapterCollection = $paymentMethodAdapterCollection;
        $this->heidelpayRequestBuilder = $heidelpayRequestBuilder;
        $this->basketCreator = $basketCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayResponseTransfer
     */
    public function initialize(QuoteTransfer $quoteTransfer): HeidelpayResponseTransfer
    {
        $initializeRequestTransfer = $this->buildInitializeRequest($quoteTransfer);
        $paymentAdapter = $this->getPaymentMethodAdapter($quoteTransfer);

        return $this->transaction->executeTransaction($initializeRequestTransfer, $paymentAdapter);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayRequestTransfer
     */
    protected function buildInitializeRequest(QuoteTransfer $quoteTransfer)
    {
        $initializeRequestTransfer = $this->heidelpayRequestBuilder->buildEasyCreditRequest($quoteTransfer);
        $heidelpayBasketResponseTransfer = $this->basketCreator->createBasket($quoteTransfer);
        $initializeRequestTransfer->setIdBasket($heidelpayBasketResponseTransfer->getIdBasket());

        return $initializeRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws \SprykerEco\Zed\Heidelpay\Business\Payment\Transaction\Exception\InitializeNotSupportedException
     *
     * @return \SprykerEco\Zed\Heidelpay\Business\Payment\Type\PaymentWithInitializeInterface
     */
    protected function getPaymentMethodAdapter(QuoteTransfer $quoteTransfer)
    {
        $paymentMethodCode = $this->getPaymentMethodCode($quoteTransfer);

        if (!isset($this->paymentMethodAdapterCollection[$paymentMethodCode])) {
            throw new InitializeNotSupportedException(
                sprintf(static::ERROR_MESSAGE_INITIALIZE_TRANSACTION_NOT_SUPPORTED, $paymentMethodCode)
            );
        }

        return $this->paymentMethodAdapterCollection[$paymentMethodCode];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getPaymentMethodCode(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getPayment()->getPaymentMethod();
    }
}
