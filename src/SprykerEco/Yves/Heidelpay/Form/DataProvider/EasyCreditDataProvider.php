<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Form\DataProvider;

use Generated\Shared\Transfer\HeidelpayEasyCreditPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Yves\Heidelpay\Form\EasyCreditSubForm;
use SprykerEco\Yves\Heidelpay\HeidelpayConfig;

class EasyCreditDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @var \SprykerEco\Yves\Heidelpay\HeidelpayConfig
     */
    protected $heidelPayConfig;

    /**
     * @param \SprykerEco\Yves\Heidelpay\HeidelpayConfig $heidelPayConfig
     */
    public function __construct(HeidelpayConfig $heidelPayConfig)
    {
        $this->heidelPayConfig = $heidelPayConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPayment() === null) {
            $quoteTransfer->setPayment(new PaymentTransfer());
        }

        if ($quoteTransfer->getPayment()->getHeidelpayEasyCredit() !== null) {
            return $quoteTransfer;
        }

        $quoteTransfer->getPayment()->setHeidelpayEasyCredit(new HeidelpayEasyCreditPaymentTransfer());

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getHeidelpayPayment()) {
            $legalText = $quoteTransfer->getHeidelpayPayment()->getLegalText();
        }

        return [
            EasyCreditSubForm::VARS_KEY_LEGAL_TEXT => $legalText ?? '',
            EasyCreditSubForm::VARS_KEY_LOGO_URL => $this->heidelPayConfig->getEasyCreditLogoUrl(),
            EasyCreditSubForm::VARS_KEY_INFO_LINK => $this->heidelPayConfig->getEasyCreditInfoLink(),
        ];
    }
}
