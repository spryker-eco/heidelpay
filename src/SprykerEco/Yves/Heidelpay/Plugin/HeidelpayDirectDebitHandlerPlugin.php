<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayFactory getFactory()
 */
class HeidelpayDirectDebitHandlerPlugin extends AbstractPlugin implements StepHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `QuoteTransfer` with DirectDebit payment method transfer.
     * - Expands `QuoteTransfer` with registered direct debit account.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addToDataClass(Request $request, AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createHeidelpayDirectDebitHandler()
            ->addPaymentToQuote($quoteTransfer);
    }
}
